document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('admin-route-map');
    const dataElement = document.getElementById('admin-route-data');
    if (!mapElement || !dataElement || typeof L === 'undefined') return;

    const allStops = JSON.parse(dataElement.textContent || '[]');
    const saveRouteUrl = mapElement.dataset.saveUrl;
    const dateSelect = document.getElementById('route-date-filter');
    const collectorSelect = document.getElementById('route-collector-filter');
    const stopList = document.getElementById('route-stop-list');
    const stopCount = document.getElementById('route-stop-count');
    const estimate = document.getElementById('route-road-estimate');
    const message = document.getElementById('route-planner-message');
    const optimizeButton = document.getElementById('optimize-route-button');
    const saveButton = document.getElementById('save-route-button');
    const map = L.map('admin-route-map').setView([23.8103, 90.4125], 11);
    const mapLayers = L.layerGroup().addTo(map);
    let currentStops = [];
    let requestSerial = 0;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    function timeRank(value) {
        const match = String(value || '').match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
        if (!match) return 9999;
        let hour = Number(match[1]) % 12;
        if (match[3].toUpperCase() === 'PM') hour += 12;
        return hour * 60 + Number(match[2]);
    }

    function orderedExisting(stops) {
        return [...stops].sort(function (a, b) {
            const aOrder = a.routeOrder === null ? 9999 : a.routeOrder;
            const bOrder = b.routeOrder === null ? 9999 : b.routeOrder;
            return aOrder - bOrder || timeRank(a.time) - timeRank(b.time) || a.id - b.id;
        });
    }

    function setMessage(text, tone) {
        const classes = {
            blue: 'border-blue-200 bg-blue-50 text-blue-800',
            green: 'border-green-200 bg-green-50 text-green-800',
            amber: 'border-amber-200 bg-amber-50 text-amber-800',
            red: 'border-red-200 bg-red-50 text-red-800'
        };
        message.className = 'mt-4 rounded-xl border px-4 py-3 text-sm ' + (classes[tone] || classes.blue);
        message.textContent = text;
    }

    function markerIcon(number) {
        return L.divIcon({
            className: '',
            html: '<div class="route-number-icon">' + number + '</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
    }

    function addStopCard(stop, index) {
        const card = document.createElement('div');
        card.className = 'flex items-start gap-3 rounded-xl border border-gray-200 bg-white p-3 shadow-sm';

        const number = document.createElement('span');
        number.className = 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white';
        number.textContent = String(index + 1);

        const details = document.createElement('div');
        details.className = 'min-w-0 flex-1';
        const title = document.createElement('div');
        title.className = 'truncate text-sm font-bold text-gray-900';
        title.textContent = stop.patient;
        const time = document.createElement('div');
        time.className = 'mt-0.5 text-xs font-semibold text-indigo-700';
        time.textContent = stop.time;
        const address = document.createElement('div');
        address.className = 'mt-1 line-clamp-2 text-xs leading-5 text-gray-500';
        address.textContent = stop.address;
        details.append(title, time, address);

        const controls = document.createElement('div');
        controls.className = 'flex shrink-0 flex-col gap-1';
        [['↑', -1, 'Move earlier'], ['↓', 1, 'Move later']].forEach(function (control) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 disabled:opacity-30';
            button.textContent = control[0];
            button.title = control[2];
            button.disabled = index + control[1] < 0 || index + control[1] >= currentStops.length;
            button.addEventListener('click', function () {
                const target = index + control[1];
                const reordered = [...currentStops];
                [reordered[index], reordered[target]] = [reordered[target], reordered[index]];
                currentStops = reordered;
                renderRoute(currentStops);
                saveButton.disabled = false;
                setMessage('Stop order adjusted. Save it when ready.', 'amber');
            });
            controls.appendChild(button);
        });

        card.append(number, details, controls);
        stopList.appendChild(card);
    }

    function distanceBetween(from, to) {
        const lat1 = from.lat * Math.PI / 180;
        const lat2 = to.lat * Math.PI / 180;
        const deltaLat = (to.lat - from.lat) * Math.PI / 180;
        const deltaLng = (to.lng - from.lng) * Math.PI / 180;
        const value = Math.sin(deltaLat / 2) ** 2
            + Math.cos(lat1) * Math.cos(lat2) * Math.sin(deltaLng / 2) ** 2;
        return 6371 * 2 * Math.atan2(Math.sqrt(value), Math.sqrt(1 - value));
    }

    function fallbackDistance(stops) {
        let kilometres = 0;
        for (let index = 1; index < stops.length; index++) {
            kilometres += distanceBetween(stops[index - 1], stops[index]);
        }
        return kilometres;
    }

    function readableDuration(seconds) {
        const minutes = Math.max(1, Math.round(seconds / 60));
        if (minutes < 60) return minutes + ' min';
        return Math.floor(minutes / 60) + ' hr ' + (minutes % 60) + ' min';
    }

    async function renderRoute(stops) {
        const thisRequest = ++requestSerial;
        mapLayers.clearLayers();
        stopList.innerHTML = '';
        stopCount.textContent = String(stops.length);
        estimate.textContent = stops.length ? 'Calculating…' : '—';
        optimizeButton.disabled = stops.length < 2;
        saveButton.disabled = stops.length === 0;

        if (!stops.length) {
            map.setView([23.8103, 90.4125], 11);
            setMessage('There are no assigned appointments with GPS pins for this selection.', 'amber');
            return;
        }

        stops.forEach(function (stop, index) {
            addStopCard(stop, index);
            const marker = L.marker([stop.lat, stop.lng], { icon: markerIcon(index + 1) }).addTo(mapLayers);
            const popup = document.createElement('div');
            const strong = document.createElement('strong');
            strong.textContent = (index + 1) + '. ' + stop.patient;
            const detail = document.createElement('div');
            detail.textContent = stop.time + ' · ' + stop.address;
            popup.append(strong, detail);
            marker.bindPopup(popup);
        });

        const bounds = L.latLngBounds(stops.map(stop => [stop.lat, stop.lng]));
        map.fitBounds(bounds, { padding: [35, 35], maxZoom: 16 });

        if (stops.length === 1) {
            estimate.textContent = 'Single stop';
            setMessage('One mapped appointment is ready for this collector.', 'blue');
            return;
        }

        const coordinates = stops.map(stop => stop.lng + ',' + stop.lat).join(';');
        try {
            const response = await fetch(
                'https://router.project-osrm.org/route/v1/driving/' + coordinates
                + '?overview=full&geometries=geojson&steps=false'
            );
            const data = await response.json();
            if (!response.ok || data.code !== 'Ok' || !data.routes?.length) {
                throw new Error('No road route returned');
            }
            if (thisRequest !== requestSerial) return;

            L.geoJSON(data.routes[0].geometry, {
                style: { color: '#4f46e5', weight: 6, opacity: 0.8 }
            }).addTo(mapLayers);
            estimate.textContent = (data.routes[0].distance / 1000).toFixed(1) + ' km · ' + readableDuration(data.routes[0].duration);
            setMessage('Road route loaded in the numbered stop order.', 'green');
        } catch (error) {
            if (thisRequest !== requestSerial) return;
            L.polyline(stops.map(stop => [stop.lat, stop.lng]), {
                color: '#94a3b8', weight: 4, dashArray: '8 8'
            }).addTo(mapLayers);
            estimate.textContent = fallbackDistance(stops).toFixed(1) + ' km approx.';
            setMessage('The road service is unavailable, so the map is showing an approximate line. You can still save the stop order.', 'amber');
        }
    }

    function populateCollectors() {
        const date = dateSelect.value;
        const available = allStops.filter(stop => stop.date === date);
        const collectors = new Map();
        available.forEach(stop => collectors.set(String(stop.collectorId), stop.collectorName));
        collectorSelect.innerHTML = '';

        if (!collectors.size) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No mapped collectors';
            collectorSelect.appendChild(option);
            currentStops = [];
            renderRoute(currentStops);
            return;
        }

        [...collectors.entries()]
            .sort((a, b) => a[1].localeCompare(b[1]))
            .forEach(function (entry) {
                const option = document.createElement('option');
                option.value = entry[0];
                option.textContent = entry[1];
                collectorSelect.appendChild(option);
            });
        loadSelection();
    }

    function loadSelection() {
        currentStops = orderedExisting(allStops.filter(function (stop) {
            return stop.date === dateSelect.value
                && String(stop.collectorId) === collectorSelect.value;
        }));
        renderRoute(currentStops);
    }

    async function optimizeRoute() {
        if (currentStops.length < 2) return;
        optimizeButton.disabled = true;
        optimizeButton.textContent = 'Optimizing roads…';
        setMessage('Comparing travel times between every appointment…', 'blue');

        const byTime = [...currentStops].sort((a, b) => timeRank(a.time) - timeRank(b.time) || a.id - b.id);
        const coordinates = byTime.map(stop => stop.lng + ',' + stop.lat).join(';');
        let durations = null;

        try {
            const response = await fetch(
                'https://router.project-osrm.org/table/v1/driving/' + coordinates + '?annotations=duration'
            );
            const data = await response.json();
            if (!response.ok || data.code !== 'Ok' || !data.durations) throw new Error('No matrix');
            durations = data.durations;
        } catch (error) {
            durations = byTime.map(from => byTime.map(to => distanceBetween(from, to)));
        }

        const remaining = byTime.map((stop, index) => ({ stop, index }));
        const optimized = [remaining.shift()];

        while (remaining.length) {
            const current = optimized[optimized.length - 1];
            const earliestRank = Math.min(...remaining.map(item => timeRank(item.stop.time)));
            const eligible = remaining.filter(item => timeRank(item.stop.time) === earliestRank);
            eligible.sort(function (a, b) {
                return (durations[current.index][a.index] ?? Number.MAX_SAFE_INTEGER)
                    - (durations[current.index][b.index] ?? Number.MAX_SAFE_INTEGER);
            });
            const next = eligible[0];
            optimized.push(next);
            remaining.splice(remaining.findIndex(item => item.index === next.index), 1);
        }

        currentStops = optimized.map(item => item.stop);
        await renderRoute(currentStops);
        optimizeButton.disabled = false;
        optimizeButton.textContent = 'Optimize road route';
        saveButton.disabled = false;
        setMessage('Optimized by appointment time first, then shortest road travel between compatible stops. Save this order to send it to the collector.', 'green');
    }

    async function saveRoute() {
        if (!currentStops.length) return;
        saveButton.disabled = true;
        saveButton.textContent = 'Saving…';

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) throw new Error('CSRF token is missing from the application layout.');

            const routeForm = new FormData();
            routeForm.append('_token', csrfMeta.content);
            routeForm.append('route_date', dateSelect.value);
            routeForm.append('collector_id', String(Number(collectorSelect.value)));
            currentStops.forEach(stop => routeForm.append('ordered_ids[]', String(stop.id)));

            const response = await fetch(saveRouteUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: routeForm
            });
            const responseText = await response.text();
            let data = null;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                if (response.redirected) {
                    throw new Error('Your login session expired. Sign in again and retry.');
                }
                throw new Error('Route save failed with HTTP ' + response.status + '. Clear the Laravel route cache and retry.');
            }
            if (!response.ok) {
                const validationMessage = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : data.message;
                throw new Error(validationMessage || 'Could not save route');
            }
            currentStops.forEach((stop, index) => stop.routeOrder = index + 1);
            setMessage(data.message + ' It now appears in this order for the collector.', 'green');
        } catch (error) {
            setMessage(error.message || 'The route could not be saved.', 'red');
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Save stop order';
        }
    }

    dateSelect.addEventListener('change', populateCollectors);
    collectorSelect.addEventListener('change', loadSelection);
    optimizeButton.addEventListener('click', optimizeRoute);
    saveButton.addEventListener('click', saveRoute);

    const today = new Date().toISOString().slice(0, 10);
    if ([...dateSelect.options].some(option => option.value === today)) {
        dateSelect.value = today;
    }
    populateCollectors();
    window.setTimeout(function () { map.invalidateSize(); }, 200);
});

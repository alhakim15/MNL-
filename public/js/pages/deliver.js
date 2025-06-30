function closeModal() {
  const modal = document.getElementById('deliveryModal');
  if (modal) modal.style.display = 'none';
}

// DOM elements
const fromCitySelect = document.getElementById('from_city_id');
const toCitySelect = document.getElementById('to_city_id');
const shipSelect = document.getElementById('ship_id');
const capacityInfo = document.getElementById('capacity-info');
const routeInfo = document.getElementById('route-info');
const shipLoading = document.getElementById('ship-loading');
const weightInput = document.getElementById('weight');

// Auto-hide messages after 3 seconds
setTimeout(() => {
  const errorMsg = document.querySelector('.error-message');
  if (errorMsg) errorMsg.style.display = 'none';

  const successMsg = document.querySelector('.success-message');
  if (successMsg) successMsg.style.display = 'none';
}, 3000);

// Prevent selecting same city for from and to
fromCitySelect.addEventListener('change', function () {
  const selectedFrom = this.value;
  
  Array.from(toCitySelect.options).forEach(opt => {
    opt.hidden = false;
    if (opt.value === selectedFrom && selectedFrom !== "") {
      opt.hidden = true;
      if (toCitySelect.value === selectedFrom) {
        toCitySelect.value = "";
        loadShipsByRoute(); // Reset ships when destination changes
      }
    }
  });
  
  loadShipsByRoute();
});

toCitySelect.addEventListener('change', function () {
  loadShipsByRoute();
});

// Load ships based on selected route
async function loadShipsByRoute() {
  const fromCityId = fromCitySelect.value;
  const toCityId = toCitySelect.value;
  
  // Clear previous data
  routeInfo.innerHTML = '';
  capacityInfo.innerHTML = '';
  
  if (!fromCityId || !toCityId) {
    resetShipSelect();
    return;
  }
  
  // Show loading
  shipLoading.style.display = 'block';
  shipSelect.disabled = true;
  resetShipSelect('Memuat kapal...');
  
  try {
    const response = await fetch(`/api/ships-by-route?from_city_id=${fromCityId}&to_city_id=${toCityId}`);
    const data = await response.json();
    
    if (data.success) {
      populateShips(data.ships);
      showRouteInfo(data.route_info);
      
      if (data.ships.length === 0) {
        showMessage('Tidak ada kapal yang melayani rute ini.', 'warning');
      }
    } else {
      showMessage('Gagal memuat data kapal.', 'error');
      resetShipSelect();
    }
  } catch (error) {
    console.error('Error loading ships:', error);
    showMessage('Terjadi kesalahan saat memuat data kapal.', 'error');
    resetShipSelect();
  } finally {
    shipLoading.style.display = 'none';
  }
}

// Populate ship options
function populateShips(ships) {
  shipSelect.innerHTML = '<option value="">Pilih Kapal</option>';
  
  ships.forEach(ship => {
    const option = document.createElement('option');
    option.value = ship.id;
    option.textContent = ship.display_text;
    option.setAttribute('data-remaining', ship.remaining_capacity);
    option.setAttribute('data-max', ship.max_weight);
    option.setAttribute('data-current', ship.current_load);
    option.setAttribute('data-percentage', ship.capacity_percentage);
    
    // Disable if ship is at capacity
    if (ship.remaining_capacity <= 0) {
      option.disabled = true;
      option.textContent += ' (Penuh)';
    }
    
    shipSelect.appendChild(option);
  });
  
  shipSelect.disabled = ships.length === 0;
}

// Reset ship select
function resetShipSelect(placeholder = 'Pilih rute terlebih dahulu') {
  shipSelect.innerHTML = `<option value="">${placeholder}</option>`;
  shipSelect.disabled = true;
}

// Show route information
function showRouteInfo(routeInfo) {
  if (!routeInfo) return;
  
  const infoHtml = `
    <div class="route-details">
      <i class="fas fa-route"></i>
      <span>Jarak: ${routeInfo.distance} km</span>
      <span>Estimasi: ${routeInfo.estimated_hours} jam</span>
    </div>
  `;
  
  document.getElementById('route-info').innerHTML = infoHtml;
}

// Handle ship selection change
shipSelect.addEventListener('change', function () {
  const selectedOption = this.options[this.selectedIndex];
  const remaining = selectedOption.getAttribute('data-remaining');
  const max = selectedOption.getAttribute('data-max');
  const current = selectedOption.getAttribute('data-current');
  const percentage = selectedOption.getAttribute('data-percentage');

  if (remaining !== null && remaining !== undefined) {
    const capacityHtml = `
      <div class="capacity-details">
        <div class="capacity-bar">
          <div class="capacity-fill" style="width: ${percentage}%"></div>
        </div>
        <div class="capacity-text">
          <span>Terpakai: ${current} ton</span>
          <span>Sisa: ${remaining} ton</span>
          <span>Total: ${max} ton</span>
        </div>
      </div>
    `;
    
    capacityInfo.innerHTML = capacityHtml;
    capacityInfo.className = `capacity-notice ${getCapacityClass(percentage)}`;
    
    // Validate weight input against remaining capacity
    validateWeight();
  } else {
    capacityInfo.innerHTML = '';
  }
});

// Weight input validation
weightInput.addEventListener('input', validateWeight);

function validateWeight() {
  const weight = parseFloat(weightInput.value);
  const selectedOption = shipSelect.options[shipSelect.selectedIndex];
  const remaining = parseFloat(selectedOption.getAttribute('data-remaining'));
  
  if (weight && remaining && weight > remaining) {
    weightInput.setCustomValidity(`Berat melebihi kapasitas kapal yang tersisa (${remaining} ton)`);
    showMessage(`Berat melebihi kapasitas kapal yang tersisa (${remaining} ton)`, 'warning');
  } else {
    weightInput.setCustomValidity('');
    hideMessage('warning');
  }
}

// Get capacity class based on percentage
function getCapacityClass(percentage) {
  if (percentage >= 90) return 'capacity-critical';
  if (percentage >= 70) return 'capacity-warning';
  return 'capacity-good';
}

// Show message
function showMessage(message, type) {
  hideMessage(); // Hide existing messages
  
  const messageDiv = document.createElement('div');
  messageDiv.className = `${type}-message dynamic-message`;
  messageDiv.innerHTML = `<i class="fas fa-${getIconClass(type)}"></i> ${message}`;
  
  const form = document.querySelector('form');
  form.insertBefore(messageDiv, form.firstChild);
  
  // Auto-hide after 5 seconds
  setTimeout(() => hideMessage(type), 5000);
}

// Hide message
function hideMessage(type = null) {
  const selector = type ? `.${type}-message.dynamic-message` : '.dynamic-message';
  const messages = document.querySelectorAll(selector);
  messages.forEach(msg => msg.remove());
}

// Get icon class for message type
function getIconClass(type) {
  switch(type) {
    case 'error': return 'exclamation-circle';
    case 'warning': return 'exclamation-triangle';
    case 'success': return 'check-circle';
    default: return 'info-circle';
  }
}
 function closeModal() {
      const modal = document.getElementById('deliveryModal');
      if (modal) modal.style.display = 'none';
    }

    const shipSelect = document.getElementById('ship_id');
    const capacityInfo = document.getElementById('capacity-info');

    shipSelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const remaining = selectedOption.getAttribute('data-remaining');
      const max = selectedOption.getAttribute('data-max');

      if (remaining !== null) {
        capacityInfo.textContent = `Sisa kapasitas kapal: ${remaining} ton dari total ${max} ton`;
        capacityInfo.style.color = (remaining < 5) ? 'red' : 'green';
      } else {
        capacityInfo.textContent = '';
      }
    });

    setTimeout(() => {
      const errorMsg = document.querySelector('.error-message');
      if (errorMsg) errorMsg.style.display = 'none';

      const successMsg = document.querySelector('.success-message');
      if (successMsg) successMsg.style.display = 'none';
    }, 3000);

    const fromCitySelect = document.getElementById('from_city_id');
    const toCitySelect = document.getElementById('to_city_id');

    fromCitySelect.addEventListener('change', function () {
      const selectedFrom = this.value;

      Array.from(toCitySelect.options).forEach(opt => {
        opt.hidden = false;
        if (opt.value === selectedFrom && selectedFrom !== "") {
          opt.hidden = true;
          if (toCitySelect.value === selectedFrom) {
            toCitySelect.value = "";
          }
        }
      });
    });


    
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('pcg-customizer-form');

  function updateCSSVariables() {
      const headerBg = document.getElementById('header_bg').value;
      const sideBg = document.getElementById('side_bg').value;
      const cornerBg = document.getElementById('corner_bg').value;
      const cellBg = document.getElementById('cell_bg').value;
      const fontColor = document.getElementById('font_color').value;
      const fontSize = document.getElementById('font_size').value;
      
      document.documentElement.style.setProperty('--header-bg', headerBg);
      document.documentElement.style.setProperty('--side-bg', sideBg);
      document.documentElement.style.setProperty('--corner-bg', cornerBg);
      document.documentElement.style.setProperty('--cell-bg', cellBg);
      document.documentElement.style.setProperty('--font-color', fontColor);
      document.documentElement.style.setProperty('--font-size', fontSize + 'px');

      // Reflow erzwingen (hilft z. B. in Firefox)
      document.body.offsetHeight;
  }

  if (form) {
      form.addEventListener('input', updateCSSVariables);
      form.addEventListener('change', updateCSSVariables);
  }

  // AJAX: Karte neu laden, wenn sich die Zeilenanzahl ändert
  const rowsInput = document.getElementById('rows');
  if (rowsInput) {
      const updateCard = function() {
          const rows = rowsInput.value;
          jQuery.post(pcg_ajax.ajax_url, {
              action: 'pcg_update_card',
              rows: rows
          }, function(response) {
              document.getElementById('card-output').innerHTML = response;
          });
      };
      rowsInput.addEventListener('input', updateCard);
      rowsInput.addEventListener('change', updateCard);
  }

});


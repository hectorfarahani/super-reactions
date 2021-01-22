document.addEventListener("DOMContentLoaded", function (event) {

  const nonce = document.getElementById('_wpnonce');
  const $selects = document.querySelectorAll('select');

  const $tabs = document.querySelectorAll('.srea-tab');

  $tabs.forEach(element => {
    element.addEventListener('click', tabActivate);
  })

  function tabActivate(e) {
    document.querySelector('.srea-tab.active').classList.remove('active');
    e.currentTarget.classList.add('active');
    showView(e.currentTarget.dataset.view);
  }

  function showView(id) {
    document.querySelector('.srea-view.active').classList.remove('active');
    document.querySelector(`#${id}`).classList.add('active');
  }


  $selects.forEach(element => {
    element.addEventListener('change', sreaSaveSettings);
  });

  function sreaSaveSettings(e) {
    const spinner = showLoader();
    e.target.insertAdjacentElement('afterend', spinner);

    const formData = new FormData();

    formData.append('action', 'srea_save_settings');
    formData.append('nonce', nonce.value);
    formData.append('option', e.target.name);
    formData.append('value', e.target.value);

    fetch(ajaxurl, {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(res => {
        e.target.parentNode.removeChild(spinner);
        const badge = showResults(res.success, res.data.results);
        e.target.insertAdjacentElement('afterend', badge );
        setTimeout(function(){
          e.target.parentNode.removeChild(badge);
        }, 500);
      })
  }

  function showLoader() {
    const loader = document.createElement('div');
    loader.className = 'srea-loader';
    return loader;
  }

  function showResults(status, text) {
    const badge = document.createElement('div');
    badge.className = status ? 'srea-badge-success' : 'srea-badge-error';
    badge.textContent = text;
    return badge;
  }

});

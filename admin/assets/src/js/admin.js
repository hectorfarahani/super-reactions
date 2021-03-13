document.addEventListener('DOMContentLoaded', function (event) {
  const nonce = document.getElementById('_wpnonce');

  const $tabs = document.querySelectorAll('.srea-tab');

  $tabs.forEach((element) => {
    element.addEventListener('click', tabActivate);
  });

  function tabActivate(e) {
    document.querySelector('.srea-tab.active').classList.remove('active');
    e.currentTarget.classList.add('active');
    showView(e.currentTarget.dataset.view);
  }

  function showView(id) {
    document.querySelector('.srea-view.active').classList.remove('active');
    document.querySelector(`#${id}`).classList.add('active');
  }

  function showLoader() {
    const loader = document.createElement('div');
    loader.className = 'srea-loader';
    return loader;
  }

  function showResults(status, text) {
    const badge = document.createElement('div');
    badge.className = status ? 'srea-result-badge srea-badge-success' : 'srea-result-badge srea-badge-error';
    badge.textContent = text;
    return badge;
  }

  class sreaModal {
    constructor() {
      this.modal = document.querySelector('#srea-settings-modal');
      this.selectingOption = '';
      this.modalFor = '';

      const $settingInitiators = document.querySelectorAll(
        '[data-srea-option]'
      );
      $settingInitiators.forEach((element) => {
        element.addEventListener('click', this.open.bind(this));
      });

      const $removers = document.querySelectorAll('.srea-remover');
      $removers.forEach((element) => {
        element.addEventListener('click', this.remove.bind(this));
      });

      const $previews = this.modal.querySelectorAll('.srea-setting-preview');
      $previews.forEach((element) => {
        element.addEventListener('click', this.select.bind(this));
      });

      const $closeBtn = document.querySelector('#srea-modal-close-btn');
      $closeBtn.addEventListener('click', this.close.bind(this));
    }

    open(e) {
      const optionName = e.currentTarget.dataset.sreaOption;
      this.selectingOption = optionName;
      this.modalFor = e.currentTarget;
      this.addTitle(optionName);
      this.modal.classList.add('visible');
      this.calculatePosition();
      window.addEventListener('resize', this.calculatePosition);
    }

    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }

    addTitle(slug) {
      const $title = document.getElementById('srea-modal-title-option-name');
      $title.insertAdjacentText('beforeend', this.capitalize(slug));
    }

    close() {
      this.modal.classList.remove('visible');
      const $title = document.getElementById('srea-modal-title-option-name');
      this.empty($title);
      this.selectingOption = null;
      this.modalFor = null;
    }

    calculatePosition() {
      const vw = Math.max(
        document.documentElement.clientWidth || 0,
        window.innerWidth || 0
      );
      const vh = Math.max(
        document.documentElement.clientHeight || 0,
        window.innerHeight || 0
      );
      this.modal.style.left = `${(vw + document.getElementById('adminmenuwrap').offsetWidth - this.modal.offsetWidth) / 2}px`;
      this.modal.style.top = `${(vh - this.modal.offsetHeight) / 2}px`;
    }

    select(event) {
      const $selectedPreview = event.currentTarget.cloneNode(true);
      const $previewWrapper = this.modalFor
        .closest('.srea-template-selector')
        .querySelector('.srea-selected-template-preview');

      this.empty($previewWrapper);
      $previewWrapper.insertAdjacentElement('beforeend', $selectedPreview);
      this.modalFor.innerText = 'Change';
      this.enableBtn(this.modalFor.parentNode.querySelector('.srea-remover'));

      const spinner = showLoader();
      const view = document.querySelector('.srea-view.active');
      view.insertAdjacentElement('afterBegin', spinner);

      const formData = new FormData();

      formData.append('action', 'srea_save_settings');
      formData.append('nonce', nonce.value);
      formData.append('option', this.selectingOption);

      console.log(event.currentTarget)
      formData.append('value', event.currentTarget.dataset.slug);
      this.close();
      fetch(ajaxurl, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((res) => {
          view.removeChild(spinner);
          const badge = showResults(res.success, res.data.results);
          view.insertAdjacentElement('afterBegin', badge);
          setTimeout(function () {
            // view.removeChild(badge);
          }, 500);
        });
    }

    remove(event) {
      const $previewWrapper = event.currentTarget
        .closest('.srea-template-selector')
        .querySelector('.srea-selected-template-preview');

      this.empty($previewWrapper);
      this.disableBtn(event.currentTarget);
    }

    disableBtn(btn) {
      btn.setAttribute('disabled', 'disabled');
    }

    enableBtn(btn) {
      btn.removeAttribute('disabled');
    }

    empty(ele) {
      ele.innerHTML = null;
    }
  }

  new sreaModal();
});

(() => {
  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');
  const year = document.getElementById('year');
  const form = document.getElementById('contactForm');
  const formStatus = document.getElementById('formStatus');

  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      const isOpen = navMenu.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', String(isOpen));
      navToggle.setAttribute(
        'aria-label',
        isOpen ? 'Cerrar menú de navegación' : 'Abrir menú de navegación'
      );
    });

    navMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  if (!form || !formStatus) {
    return;
  }

  const submitButton = form.querySelector('button[type="submit"]');
  const fields = ['nombre', 'email', 'servicio', 'mensaje'];

  function setError(fieldId, message = '') {
    const error = document.getElementById(`error-${fieldId}`);
    const field = document.getElementById(fieldId);
    if (error) error.textContent = message;
    if (field) field.setAttribute('aria-invalid', message ? 'true' : 'false');
  }

  function clearErrors() {
    fields.forEach((id) => setError(id));
    formStatus.textContent = '';
    formStatus.className = 'form-status';
  }

  function validate() {
    const nombre = document.getElementById('nombre');
    const email = document.getElementById('email');
    const servicio = document.getElementById('servicio');
    const mensaje = document.getElementById('mensaje');
    let valid = true;

    if (!nombre || nombre.value.trim().length < 2) {
      setError('nombre', 'Escribe un nombre de al menos 2 caracteres.');
      valid = false;
    }
    if (!email || !email.validity.valid) {
      setError('email', 'Escribe un correo electrónico válido.');
      valid = false;
    }
    if (!servicio || !servicio.value) {
      setError('servicio', 'Selecciona un servicio.');
      valid = false;
    }
    if (!mensaje || mensaje.value.trim().length < 15) {
      setError('mensaje', 'Describe tu proyecto con al menos 15 caracteres.');
      valid = false;
    }

    return valid;
  }

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrors();

    if (!validate()) {
      formStatus.textContent = 'Revisa los campos marcados antes de continuar.';
      formStatus.classList.add('is-error');
      return;
    }

    if (submitButton) {
      submitButton.disabled = true;
      submitButton.textContent = 'Enviando…';
    }

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(form),
      });
      const data = await response.json();

      if (!response.ok || !data.ok) {
        Object.entries(data.errors || {}).forEach(([field, message]) => {
          setError(field, String(message));
        });
        throw new Error(data.message || 'No fue posible enviar la solicitud.');
      }

      formStatus.textContent = data.message;
      formStatus.classList.add('is-success');
      form.reset();
    } catch (error) {
      formStatus.textContent = error instanceof Error
        ? error.message
        : 'No fue posible enviar la solicitud.';
      formStatus.classList.add('is-error');
    } finally {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = 'Enviar solicitud';
      }
    }
  });
})();


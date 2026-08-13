<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

$formFlash = $_SESSION['form_flash'] ?? null;
unset($_SESSION['form_flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- SEO básico -->
  <title>GalaxyInterMedia | Contenido, marca y marketing digital</title>
  <meta name="description" content="GalaxyInterMedia ayuda a streamers y proyectos emergentes a crecer con producción de contenido, desarrollo de marca y marketing digital.">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://www.galaxyintermedia.net/">

  <!-- Open Graph básico -->
  <meta property="og:title" content="GalaxyInterMedia | Contenido, marca y marketing digital">
  <meta property="og:description" content="Creamos contenido, construimos marcas y desarrollamos estrategias digitales para creadores y proyectos emergentes.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.galaxyintermedia.net/">

  <meta name="theme-color" content="#08080d">

  <link rel="stylesheet" href="styles.css">
  <script src="script.js" defer></script>


  <!-- Datos estructurados básicos -->
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"Organization",
    "name":"GalaxyInterMedia",
    "url":"https://www.galaxyintermedia.net/",
    "description":"Estudio creativo híbrido de producción de contenido, desarrollo de marca y marketing digital para streamers y proyectos emergentes."
  }
  </script>
</head>

<body>
  <a class="skip-link" href="#contenido">Saltar al contenido principal</a>

  <header class="site-header">
    <div class="container nav">
      <a class="brand" href="#inicio" aria-label="GalaxyInterMedia, ir al inicio">
        <span class="brand-mark brand-fallback" aria-hidden="true">GI</span>
        <span>GalaxyInterMedia</span>
      </a>

      <button class="nav-toggle"
              id="navToggle"
              type="button"
              aria-expanded="false"
              aria-controls="navMenu"
              aria-label="Abrir menú de navegación">
        Menú
      </button>

      <nav aria-label="Navegación principal">
        <ul class="nav-links" id="navMenu">
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#beneficios">Beneficios</a></li>
          <li><a href="#confianza">Cómo trabajamos</a></li>
          <li><a href="#contacto" class="btn btn-primary">Solicitar propuesta</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main id="contenido">
    <!-- HERO -->
    <section class="hero" id="inicio">
      <div class="container hero-grid">
        <div>
          <span class="eyebrow">Contenido + Marca + Marketing</span>

          <h1>
            Convertimos ideas en <strong>marcas que la gente recuerda.</strong>
          </h1>

          <p>
            GalaxyInterMedia ayuda a streamers, creadores y proyectos emergentes
            a construir una presencia digital más clara, profesional y estratégica
            mediante contenido, identidad de marca y marketing digital.
          </p>

          <div class="hero-actions">
            <a class="btn btn-primary" href="#contacto">Solicita una propuesta</a>
            <a class="btn btn-secondary" href="#servicios">Ver servicios</a>
          </div>

          <div class="hero-note">
            Estrategia pensada para crecer con identidad, no sólo para acumular publicaciones.
          </div>
        </div>

        <div class="cosmic-card" aria-label="Ilustración conceptual de Gary, mascota de GalaxyInterMedia">
          <div class="stars" aria-hidden="true"></div>
          <div class="orbit" aria-hidden="true"></div>
          <div class="mascot mascot-fallback" role="img" aria-label="Espacio reservado para Gary, mascota de GalaxyInterMedia">
            <span>G</span>
            <small>Gary</small>
          </div>
        </div>
      </div>
    </section>

    <!-- PROPUESTA DE VALOR -->
    <section class="section" aria-labelledby="valor-title">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Propuesta de valor</span>
          <h2 id="valor-title">No sólo hacemos contenido. Construimos dirección.</h2>
          <p>
            Integramos creatividad, identidad visual y estrategia digital para que
            cada pieza tenga una función dentro del crecimiento de tu proyecto.
          </p>
        </div>

        <div class="value-strip">
          <div class="value-item">
            <span>Para quién</span>
            <strong>Creadores, streamers y proyectos emergentes.</strong>
          </div>

          <div class="value-item">
            <span>Qué resolvemos</span>
            <strong>Falta de identidad, consistencia y estrategia en redes.</strong>
          </div>

          <div class="value-item">
            <span>Qué obtienes</span>
            <strong>Contenido con intención, marca coherente y una ruta de crecimiento.</strong>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICIOS -->
    <section class="section" id="servicios" aria-labelledby="servicios-title">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Servicios</span>
          <h2 id="servicios-title">Lo que podemos construir contigo</h2>
          <p>
            Elige el área que más necesita tu proyecto o combínalas para desarrollar
            una presencia digital más completa.
          </p>
        </div>

        <div class="cards">
          <article class="card">
            <div class="card-image card-image--production" role="img" aria-label="Producción audiovisual">🎥</div>
            <div class="card-icon" aria-hidden="true">🎬</div>
            <h3>Producción de contenido</h3>
            <p>
              Creamos piezas pensadas para captar atención, comunicar mejor y mantener
              una presencia constante en plataformas digitales.
            </p>
            <ul>
              <li>Video corto y contenido para redes</li>
              <li>Edición y adaptación por plataforma</li>
              <li>Conceptos y guiones de contenido</li>
            </ul>
          </article>

          <article class="card">
            <div class="card-image card-image--brand" role="img" aria-label="Desarrollo de identidad de marca">✦</div>
            <div class="card-icon" aria-hidden="true">✦</div>
            <h3>Desarrollo de marca</h3>
            <p>
              Convertimos una idea visual dispersa en una identidad reconocible,
              consistente y preparada para crecer.
            </p>
            <ul>
              <li>Identidad y concepto visual</li>
              <li>Tono y personalidad de marca</li>
              <li>Sistemas gráficos para contenido</li>
            </ul>
          </article>

          <article class="card">
            <div class="card-image card-image--marketing" role="img" aria-label="Analítica y marketing digital">↗</div>
            <div class="card-icon" aria-hidden="true">📈</div>
            <h3>Marketing digital</h3>
            <p>
              Diseñamos acciones orientadas a posicionamiento, comunidad y crecimiento
              para que tus publicaciones tengan un objetivo claro.
            </p>
            <ul>
              <li>Estrategia de contenidos</li>
              <li>Planeación por canal y audiencia</li>
              <li>Métricas y optimización</li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <!-- BENEFICIOS -->
    <section class="section" id="beneficios" aria-labelledby="beneficios-title">
      <div class="container benefits">
        <div class="section-head">
          <span class="eyebrow">Beneficios</span>
          <h2 id="beneficios-title">Tu proyecto necesita algo más que “publicar por publicar”.</h2>
          <p>
            Nuestro enfoque busca que contenido, identidad y estrategia trabajen juntos
            para que tu presencia digital sea más fácil de entender, recordar y seguir.
          </p>
        </div>

        <div class="benefit-list">
          <div class="benefit">
            <div class="benefit-number" aria-hidden="true">01</div>
            <div>
              <h3>Mayor coherencia</h3>
              <p>Tu contenido deja de parecer una colección de piezas aisladas y comienza a sentirse parte de una misma marca.</p>
            </div>
          </div>

          <div class="benefit">
            <div class="benefit-number" aria-hidden="true">02</div>
            <div>
              <h3>Mejor dirección creativa</h3>
              <p>Cada formato parte de una intención concreta: atraer, explicar, posicionar o generar comunidad.</p>
            </div>
          </div>

          <div class="benefit">
            <div class="benefit-number" aria-hidden="true">03</div>
            <div>
              <h3>Decisiones más estratégicas</h3>
              <p>La planeación se basa en audiencia, canal y objetivo, no únicamente en tendencias aisladas.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONFIANZA / FORMA DE TRABAJO -->
    <section class="section" id="confianza" aria-labelledby="confianza-title">
      <div class="container">
        <div class="trust">
          <div>
            <span class="eyebrow">Señal de confianza</span>
            <h2 id="confianza-title">Trabajamos como una extensión de tu proyecto.</h2>
            <p>
              Antes de producir por producir, buscamos entender qué quieres construir,
              a quién quieres llegar y qué papel debe cumplir cada contenido dentro de tu estrategia.
            </p>
          </div>

          <div class="trust-points" aria-label="Características de atención">
            <div><strong>Atención enfocada en tu proyecto</strong></div>
            <div><strong>Proceso creativo con objetivo estratégico</strong></div>
            <div><strong>Comunicación clara para avanzar por etapas</strong></div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACTO -->
    <section class="section" id="contacto" aria-labelledby="contacto-title">
      <div class="container contact-grid">
        <div class="contact-copy">
          <span class="eyebrow">Contacto</span>
          <h2 id="contacto-title">Cuéntanos qué quieres construir.</h2>
          <p>
            Envíanos los datos básicos de tu proyecto. Revisaremos tu solicitud y nos pondremos
            en contacto contigo para definir el siguiente paso.
          </p>
        </div>

        <form id="contactForm" action="guardar.php" method="post" novalidate>
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <div class="hp-field" aria-hidden="true">
            <label for="website">Sitio web</label>
            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
          </div>
          <div class="form-row">
            <div class="field">
              <label for="nombre">Nombre *</label>
              <input
                id="nombre"
                name="nombre"
                type="text"
                autocomplete="name"
                minlength="2"
                maxlength="120"
                required
                maxlength="190"
                aria-describedby="error-nombre"
              >
              <div class="error" id="error-nombre"></div>
            </div>

            <div class="field">
              <label for="email">Correo electrónico *</label>
              <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                aria-describedby="error-email"
              >
              <div class="error" id="error-email"></div>
            </div>
          </div>

          <div class="field">
            <label for="servicio">Servicio de interés *</label>
            <select id="servicio" name="servicio" required aria-describedby="error-servicio">
              <option value="">Selecciona una opción</option>
              <option>Producción de contenido</option>
              <option>Desarrollo de marca</option>
              <option>Marketing digital</option>
              <option>Proyecto integral</option>
            </select>
            <div class="error" id="error-servicio"></div>
          </div>

          <div class="field">
            <label for="mensaje">Cuéntanos sobre tu proyecto *</label>
            <textarea
              id="mensaje"
              name="mensaje"
              minlength="15"
              maxlength="3000"
              required
              aria-describedby="error-mensaje"
              placeholder="¿Qué haces, qué necesitas y qué objetivo buscas alcanzar?"
            ></textarea>
            <div class="error" id="error-mensaje"></div>
          </div>

          <button class="btn btn-primary" type="submit">Enviar solicitud</button>

          <div class="form-status<?= is_array($formFlash) ? ' is-' . e((string) ($formFlash['type'] ?? 'info')) : '' ?>" id="formStatus" role="status" aria-live="polite"><?= is_array($formFlash) ? e((string) ($formFlash['message'] ?? '')) : '' ?></div>
        </form>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer-inner">
      <p>© <span id="year"></span> GalaxyInterMedia. Todos los derechos reservados.</p>

      <div class="footer-links" aria-label="Enlaces del pie de página">
        <a href="#inicio">Inicio</a>
        <a href="#servicios">Servicios</a>
        <a href="#contacto">Contacto</a>
        <a href="admin/login.php">Administración</a>
      </div>
    </div>
  </footer>

</body>
</html>

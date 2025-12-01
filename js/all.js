/**
 * okchicas.com theme scripts (vanilla).
 * Replaces legacy jQuery plugins with lightweight DOM utilities.
 */
(() => {
  const SLIDEBAR_ACTIVE_CLASS = 'is-open';
  const LOCK_CLASS = 'sidebar-locked';
  const SLIDEBAR_TEMPLATE_ID = 'sidebar-slidebar-template';
  const DEFAULT_SIDEBAR_ID = 'sidebar-offcanvas-left';
  const SCROLLBAR_VAR = '--scrollbar-compensation';
  const HEADROOM_BREAKPOINT = 927;
  const HEADROOM_OFFSET = 40;
  const HEADROOM_TOLERANCE_UP = 20;
  const BILLBOARD_SLOT_ID = 'div-gpt-ad-1624665948236-0';

  const state = {
    activeSidebar: null,
    overlay: null,
    siteContainer: document.getElementById('sidebar-site'),
    sidebars: new Map(),
  };

  function cacheSidebars() {
    document.querySelectorAll('.sidebar-slidebar[id]').forEach((sidebar) => {
      state.sidebars.set(sidebar.id, sidebar);
      sidebar.setAttribute('aria-hidden', 'true');
    });
  }

  function renderSidebarFromTemplate(id) {
    if (getSidebar(id)) {
      return getSidebar(id);
    }

    const template = document.getElementById(SLIDEBAR_TEMPLATE_ID);
    if (!template || !template.content) {
      return null;
    }

    const clone = template.content.cloneNode(true);
    document.body.appendChild(clone);
    cacheSidebars();
    return getSidebar(id);
  }

  function getSidebar(id) {
    return id ? state.sidebars.get(id) : null;
  }

  function getToggles(id) {
    return Array.from(document.querySelectorAll(`[aria-controls="${id}"]`));
  }

  function ensureOverlay() {
    if (state.overlay) {
      return state.overlay;
    }

    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.setAttribute('aria-hidden', 'true');
    overlay.addEventListener('click', closeSidebar);

    document.body.appendChild(overlay);
    state.overlay = overlay;
    return overlay;
  }

  function setExpanded(id, expanded) {
    getToggles(id).forEach((toggle) => {
      toggle.setAttribute('aria-expanded', String(expanded));
    });
  }

  function lockScroll() {
    const scrollbarWidth = Math.max(window.innerWidth - document.documentElement.clientWidth, 0);
    const compensation = `${scrollbarWidth}px`;

    document.documentElement.style.setProperty(SCROLLBAR_VAR, compensation);
    document.body.style.setProperty(SCROLLBAR_VAR, compensation);
    document.documentElement.classList.add(LOCK_CLASS);
    document.body.classList.add(LOCK_CLASS);
  }

  function unlockScroll() {
    document.documentElement.classList.remove(LOCK_CLASS);
    document.body.classList.remove(LOCK_CLASS);
    document.documentElement.style.removeProperty(SCROLLBAR_VAR);
    document.body.style.removeProperty(SCROLLBAR_VAR);
  }

  function openSidebar(id, { focusSearch } = {}) {
    const sidebar = getSidebar(id) || renderSidebarFromTemplate(id);
    if (!sidebar) {
      return;
    }

    if (state.activeSidebar && state.activeSidebar !== id) {
      closeSidebar();
    }

    const overlay = ensureOverlay();
    overlay.classList.add('is-visible');
    overlay.setAttribute('aria-hidden', 'false');
    lockScroll();

    sidebar.classList.add(SLIDEBAR_ACTIVE_CLASS);
    sidebar.setAttribute('aria-hidden', 'false');
    state.activeSidebar = id;
    setExpanded(id, true);

    if (focusSearch) {
      const searchInput = sidebar.querySelector('input[type="search"]');
      if (searchInput) {
        searchInput.focus();
      }
    }
  }

  function closeSidebar() {
    if (!state.activeSidebar) {
      return;
    }

    const sidebar = getSidebar(state.activeSidebar);
    if (sidebar) {
      sidebar.classList.remove(SLIDEBAR_ACTIVE_CLASS);
      sidebar.setAttribute('aria-hidden', 'true');
    }

    if (state.overlay) {
      state.overlay.classList.remove('is-visible');
      state.overlay.setAttribute('aria-hidden', 'true');
    }

    unlockScroll();
    setExpanded(state.activeSidebar, false);
    state.activeSidebar = null;
  }

  function handleToggle(event) {
    const trigger = event.currentTarget;
    const targetId = trigger.getAttribute('aria-controls') || state.activeSidebar;
    if (!targetId) {
      return;
    }

    event.preventDefault();

    const focusSearch = trigger.classList.contains('search-icon');
    if (state.activeSidebar === targetId) {
      closeSidebar();
    } else {
      openSidebar(targetId, { focusSearch });
    }
  }

  function initSlidebar() {
    cacheSidebars();

    const hasTemplate = Boolean(document.getElementById(SLIDEBAR_TEMPLATE_ID));

    if (!state.sidebars.size && !hasTemplate) {
      return;
    }

    const toggleSelector = '.sidebar-toggle-left, .sidebar-toggle-right, .sidebar-open-left, .sidebar-open-right, .sidebar-close';
    const defaultSidebarId =
      state.sidebars.size === 1
        ? state.sidebars.keys().next().value
        : hasTemplate
          ? DEFAULT_SIDEBAR_ID
          : null;
    const toggles = Array.from(document.querySelectorAll(toggleSelector));

    toggles.forEach((toggle) => {
      if (!toggle.getAttribute('aria-controls') && defaultSidebarId) {
        toggle.setAttribute('aria-controls', defaultSidebarId);
      }

      if (toggle.getAttribute('href') === '#') {
        toggle.setAttribute('role', 'button');
      }

      toggle.setAttribute('aria-expanded', 'false');
      toggle.addEventListener('click', handleToggle);
    });

    if (state.siteContainer) {
      state.siteContainer.addEventListener('click', (event) => {
        const clickedToggle = event.target.closest(toggleSelector);
        if (clickedToggle) {
          return;
        }

        if (state.activeSidebar && !event.target.closest('.sidebar-slidebar')) {
          closeSidebar();
        }
      });
    }

    document.addEventListener('keyup', (event) => {
      if (event.key === 'Escape') {
        closeSidebar();
      }
    });
  }

  function initHeadroom() {
    const header = document.querySelector('.site-header');
    if (!header) {
      return;
    }

    let enabled = false;
    let enableScheduled = false;
    let lastViewportIsMobile = null;
    let lastY = 0;
    let pendingY = 0;
    let ticking = false;
    let resizeTimer;
    let breakpointReadFrame = null;

    const readScrollY = () => window.scrollY || window.pageYOffset;
    const readViewportIsMobile = () => window.innerWidth <= HEADROOM_BREAKPOINT;

    const reset = () => {
      header.classList.remove('headroom', 'bajando', 'subiendo', 'noesarriba', 'topando');
      lastY = 0;
      pendingY = 0;
    };

    const update = (currentY) => {
      if (!enabled) {
        return;
      }

      const delta = currentY - lastY;
      const distance = Math.abs(delta);

      if (currentY <= HEADROOM_OFFSET) {
        header.classList.add('topando');
        header.classList.remove('noesarriba', 'bajando', 'subiendo');
        lastY = currentY;
        return;
      }

      header.classList.add('noesarriba');
      header.classList.remove('topando');

      if (delta > 0) {
        header.classList.add('bajando');
        header.classList.remove('subiendo');
      } else if (delta < 0 && distance >= HEADROOM_TOLERANCE_UP) {
        header.classList.add('subiendo');
        header.classList.remove('bajando');
      }

      lastY = currentY;
    };

    const scheduleUpdate = () => {
      if (ticking || !enabled) {
        return;
      }

      ticking = true;
      window.requestAnimationFrame(() => {
        if (!enabled) {
          ticking = false;
          return;
        }

        update(pendingY);
        ticking = false;
      });
    };

    const onScroll = () => {
      if (!enabled) {
        return;
      }

      pendingY = window.scrollY;
      scheduleUpdate();
    };

    const enable = () => {
      if (enabled || enableScheduled) {
        return;
      }

      enableScheduled = true;

      window.requestAnimationFrame(() => {
        enableScheduled = false;

        if (enabled) {
          return;
        }

        const startY = readScrollY();
        pendingY = startY;
        lastY = startY;
        enabled = true;
        header.classList.add('headroom');
        scheduleUpdate();
        window.addEventListener('scroll', onScroll, { passive: true });
      });
    };

    const disable = () => {
      if (!enabled && !enableScheduled) {
        return;
      }

      enabled = false;
      enableScheduled = false;
      ticking = false;
      window.removeEventListener('scroll', onScroll);
      reset();
    };

    const applyBreakpoint = () => {
      if (breakpointReadFrame) {
        return;
      }

      breakpointReadFrame = window.requestAnimationFrame(() => {
        breakpointReadFrame = null;
        const isMobile = readViewportIsMobile();
        if (isMobile === lastViewportIsMobile) {
          return;
        }

        lastViewportIsMobile = isMobile;

        if (isMobile) {
          enable();
        } else {
          disable();
        }
      });
    };

    const onResize = () => {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(applyBreakpoint, 120);
    };

    applyBreakpoint();
    window.addEventListener('resize', onResize);
  }

  function wrapFluidVideos() {
    const selectors = [
      'iframe[src*="player.vimeo.com"]',
      'iframe[src*="youtube.com"]',
      'iframe[src*="youtube-nocookie.com"]',
      'iframe[src*="kickstarter.com"][src*="video.html"]',
      'iframe[src*="screenr.com"]',
      'iframe[src*="blip.tv"]',
      'iframe[src*="dailymotion.com"]',
      'iframe[src*="viddler.com"]',
      'iframe[src*="qik.com"]',
      'iframe[src*="revision3.com"]',
      'iframe[src*="hulu.com"]',
      'iframe[src*="funnyordie.com"]',
      'iframe[src*="flickr.com"]',
      'embed[src*="v.wordpress.com"]',
    ];

    const videos = document.querySelectorAll(selectors.join(','));

    videos.forEach((video) => {
      if (video.closest('.fluid-video')) {
        return;
      }

      const fluidWrapper = document.createElement('div');
      fluidWrapper.className = 'fluid-video';

      const outerWrapper = document.createElement('div');
      outerWrapper.className = 'full-movil';

      const parent = video.parentNode;
      parent.insertBefore(outerWrapper, video);
      outerWrapper.appendChild(fluidWrapper);
      fluidWrapper.appendChild(video);
    });
  }

  function initBillboardCollapse() {
    const billboardWrap = document.querySelector('.topbillboard');
    if (!billboardWrap) {
      return;
    }

    const collapseBillboard = () => {
      const slot = document.getElementById(BILLBOARD_SLOT_ID);
      if (!slot) {
        return;
      }

      const hasQueryId = Boolean(slot.getAttribute('data-google-query-id'));
      const hasIframe = slot.querySelector('iframe');

      if (!hasQueryId && !hasIframe) {
        billboardWrap.style.display = 'none';
        billboardWrap.style.minHeight = '0';
      } else {
        billboardWrap.style.background = '#fff';
      }
    };

    collapseBillboard();

    const gpt = window.googletag;
    const hasGoogletag =
      typeof gpt !== 'undefined' && gpt && Array.isArray(gpt.cmd) && typeof gpt.pubads === 'function';

    if (hasGoogletag) {
      gpt.cmd.push(function () {
        try {
          gpt.pubads().addEventListener('slotRenderEnded', function (event) {
            const slotId =
              event && event.slot && event.slot.getSlotElementId && event.slot.getSlotElementId();

            if (slotId === BILLBOARD_SLOT_ID && event.isEmpty) {
              billboardWrap.style.display = 'none';
              billboardWrap.style.minHeight = '0';
            }
          });
        } catch (error) {
          /* ignore GPT errors */
        }
      });
    }

    window.setTimeout(collapseBillboard, 2500);
  }

  const init = () => {
    initSlidebar();
    initHeadroom();
    wrapFluidVideos();
    initBillboardCollapse();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

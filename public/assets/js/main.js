// set theme config
const themeConfig = {
    layout: 'hovered', // vertical, two-column, hovered, detached, horizontal
    direction: '', // rtl, ltr
    theme: '', // light, dark
    customizer: false, // true, false
};

let currentlayout = themeConfig.layout || 'vertical';

// theme customizer
// if (themeConfig.customizer) {
//     const customizerContainer = document.createElement('div');
//     customizerContainer.id = 'customizer-container';
//     customizerContainer.classList.add('z-[60]', 'w-full');
//     const layouts = ['vertical', 'two-column', 'hovered', 'detached', 'horizontal'];
//     const customizerComponent = `<button
//       id="customizer-btn"
//       class="fixed ltr:right-4 rtl:left-4 z-50 top-1/2 bg-primary text-n0 w-10 h-10 rounded-full flex items-center justify-center">
//       <i class="las la-cog animate-spin-slow"></i>
//     </button>
//     <div class="z-[60] duration-500 ltr:right-0 rtl:left-0" id="customizer-wrapper">
//       <aside
//         id="customizer"
//         class="w-[280px] xxxl:w-[336px] shadow-sm z-[52] duration-300 fixed ltr:right-0 rtl:left-0 h-full bg-n0 dark:bg-bg4 top-0 customizer-hide overflow-y-auto">
//         <div
//           class="p-4 flex justify-between items-center border-b border-n30 dark:border-n500">
//           <div>
//             <h5 class="h5 mb-2">Theme customizer</h5>
//             <p class="text-sm">Customize &amp; Preview in Real Time</p>
//           </div>
//           <button id="customizer-close-btn">
//             <i class="las la-times"></i>
//           </button>
//         </div>
//         <div class="p-4 border-b border-n30 dark:border-n500">
//           <span class="mb-3 text-n60 block text-sm">Themeing</span>
//           <h6 class="h6 mb-3">Mode</h6>
//           <div class="flex gap-4">
//             <button
//               class="theme-btn"
//               id="light-btn">
//               <i class="las la-sun"></i>
//               <span>Light </span>
//             </button>
//             <button
//               class="theme-btn"
//               id="dark-btn">
//               <i class="las la-moon"></i>
//               <span>Dark </span>
//             </button>
//           </div>
//         </div>
//         <div class="p-4 border-b border-n30 dark:border-n500">
//           <span class="mb-3 text-n60 block text-sm">Layout</span>
//           <h6 class="h6 mb-3">Direction</h6>
//           <div class="flex gap-4 mb-6">
//             <button
//               class="flex items-center gap-2 px-4 py-2 rounded-lg ltr:bg-primary ltr:text-n0 bg-transparent border ltr:border-n30 dark:border-n500"
//               onclick="setLtr()">
//               <span>LTR</span>
//             </button>
//             <button
//               class="flex items-center gap-2 px-3 py-2 rounded-lg rtl:bg-primary rtl:text-n0 border border-n30 dark:border-n500"
//               onclick="setRtl()">
//               <span>RTL</span>
//             </button>
//           </div>
//           <h6 class="h6 mb-3">Sidebar</h6>
//           <div class="flex gap-4 flex-wrap" id="layout-container">
//             ${layouts.map((layout) => `<button class="border border-n30 dark:border-n500 capitalize rounded-lg px-4 py-2 layout-btn ${currentlayout === layout ? 'active' : ''}">${layout}</button>`).join('')}
//           </div>
//         </div>
//       </aside>
//     </div>`;
//     customizerContainer.innerHTML = customizerComponent;
//     // Set the innerHTML of the container to your customizer HTML string
//     document.body.appendChild(customizerContainer);
//   }

  // Attach event listeners for notification and language dropdowns
  const darkModeToggle = document.getElementById('darkModeToggle');
  const lightBtn = document.getElementById('light-btn');
  const darkBtn = document.getElementById('dark-btn');
  const notificationBtn = document.getElementById('notification-btn');
  const languageBtn = document.getElementById('language-btn');
  const profileBtn = document.getElementById('profile-btn');
  const layoutBtn = document.getElementById('layout-btn');
  const layout = document.getElementById('layout');
  let layoutList;
  if (layout) {
    layoutList = layout.querySelectorAll('li');
  }
  const selectedLayout = document.getElementById('selected-layout');
  const language = document.getElementById('language');
  let languageList;
  if (language) {
    languageList = language.querySelectorAll('li');
  }
  const selectedLang = document.getElementById('selected-lang');
  const mobileSearchBtn = document.getElementById('mobile-search-btn');
  const mobileSearch = document.getElementById('mobile-search');
  // Sidebar menus
  const menuBtns = document.querySelectorAll('.menu-btn');
  const submenus = document.querySelectorAll('.submenu');

  // Images
  const logoFull = document.querySelector('.logo-full');
  const logoFull2 = document.querySelector('.logo-full2');
  const logoIcon = document.querySelector('.logo-icon');
  const logoText = document.querySelector('.logo-text');
  const qrCodeImg = document.querySelector('.qrcode-img');

  // get from localstorage or config
  const isDarkMode = themeConfig.theme === 'dark' || localStorage.getItem('darkMode') === 'true';

  // load dark images
  const loadImg = () => {
    if (document.documentElement.classList.contains('dark')) {
      logoFull.setAttribute('src', 'public/assets/images/logo-with-text.png');
      logoFull2.setAttribute('src', 'public/assets/images/logo-with-text.png');
      logoIcon.setAttribute('src', 'public/assets/images/logo.png');
      logoText.setAttribute('src', 'public/assets/images/logo-text.png');
      qrCodeImg && qrCodeImg.setAttribute('src', 'public/assets/images/qrcode.png');
    } else {
      logoFull.setAttribute('src', 'public/assets/images/logo-with-text-dark.png');
      logoFull2.setAttribute('src', 'public/assets/images/logo-with-text-dark.png');
      logoIcon.setAttribute('src', 'public/assets/images/logo-dark.png');
      logoText.setAttribute('src', 'public/assets/images/logo-text-dark.png');
      qrCodeImg && qrCodeImg.setAttribute('src', 'public/assets/images/qrcode-dark.png');
    }
  };

  // set active menu
  function setActiveMenu() {
    // add active menu
    const submenuLinks = document.querySelectorAll('.submenu-link');
    menuBtns.forEach((btn) => {
      btn.classList.remove('active');
      btn.nextElementSibling.classList.remove('submenu-show');
      btn.nextElementSibling.classList.add('submenu-hide');
    });
    // Loop through each submenu link
    submenuLinks.forEach(function (link) {
      // Get the current URL
      const currentUrl = window.location.href;
      // Get the href attribute of the submenu link
      const href = link.getAttribute('href');
      const cleanHref = href.replace(/^\.\.\//, '');
      // Check if the current URL matches the submenu link's href
      if (currentUrl.includes(cleanHref)) {
        // Add the 'active' class to the parent menu-btn
        link.classList.add('text-primary');
        const submenu = link.parentElement.parentElement;
        const menuBtn = submenu.previousElementSibling;

        submenu.classList.remove('submenu-hide');
        submenu.classList.add('submenu-show');
        menuBtn.classList.add('active');
      }
    });
  }
  const menuLinks = document.querySelectorAll('.menu-link');
  if (menuLinks) {
    menuLinks.forEach((link) => {
      const currentUrl = window.location.href;
      // Get the href attribute of the submenu link
      const href = link.getAttribute('href');
      const cleanHref = href.replace(/^\.\.\//, '');
      // Check if the current URL matches the submenu link's href
      if (currentUrl.includes(cleanHref)) {
        // Add the 'active' class to the parent menu-btn
        link.classList.add('active');
      }
    });
  }
  // change layout
  function changeLayout(layout = 'vertical') {
    // remove previous class
    document.body.classList.remove('vertical', 'two-column', 'hovered', 'horizontal', 'detached');
    if (layout) {
      document.body.classList.add(layout);
      if (selectedLayout) {
        selectedLayout.textContent = layout;
      }
    } else {
      document.body.classList.add('vertical');
      selectedLayout.textContent = 'vertical';
    }
    if (layoutList) {
      layoutList.forEach((otherItem) => {
        otherItem.classList.remove('active');
      });
    }

    const layoutBtns = document.querySelectorAll('.layout-btn');
    layoutBtns.forEach((btn) => btn.classList.remove('active'));
    layoutBtns.forEach((btn) => {
      if (currentlayout == btn.textContent.trim().toLocaleLowerCase()) {
        btn.classList.add('active');
      }
    });
    if (layoutList) {
      layoutList.forEach((item) => {
        if (item.textContent.trim().toLowerCase() === currentlayout) {
          item.classList.add('active');
        }
      });
    }
    setActiveMenu();
  }

  // rtl ltr toggle
  const setRtl = () => {
    document.documentElement.dir = 'rtl';
    localStorage.setItem('dir', 'rtl');
  };
  const setLtr = () => {
    document.documentElement.dir = 'ltr';
    localStorage.setItem('dir', 'ltr');
  };
  const handleFocus = (e) => {
    try {
      e.currentTarget.showPicker();
    } catch (error) {}
  };
  // Dark mode toggle
  document.addEventListener('DOMContentLoaded', () => {
    // Check the user's preference from local storage
    const dir = themeConfig.direction || localStorage.getItem('dir');
    const savedLayout = themeConfig.layout || localStorage.getItem('layout');
    if (dir) {
      document.documentElement.dir = dir;
    }
    if (savedLayout) {
      currentlayout = savedLayout;
    }
    changeLayout(currentlayout);
    // set layout based on localstorage

    // Set the initial theme based on the user's preference
    if (isDarkMode) {
      document.documentElement.classList.add('dark', isDarkMode);
      if (darkBtn) {
        darkBtn.classList.add('active');
        lightBtn.classList.remove('active');
        logoFull.setAttribute('src', 'public/assets/images/logo-with-text-dark.png');
        logoIcon.setAttribute('src', 'public/assets/images/logo-dark.png');
        logoText.setAttribute('src', 'public/assets/images/logo-text-dark.png');
      }
      logoFull2 && logoFull2.setAttribute('src', 'public/assets/images/logo-with-text-dark.png');
      qrCodeImg && qrCodeImg.setAttribute('src', 'public/assets/images/qrcode-dark.png');
    } else {
      darkBtn && darkBtn.classList.remove('active');
      lightBtn && lightBtn.classList.add('active');
    }
    document.documentElement.style.colorScheme = isDarkMode ? 'dark' : 'light';
    // Update the toggle button icon based on the user's preference

    // Toggle the theme when the button is clicked
    darkModeToggle &&
      darkModeToggle.addEventListener('click', () => {
        loadImg();
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('darkMode', false);
          lightBtn && lightBtn.classList.add('active');
          darkBtn && darkBtn.classList.remove('active');
          document.documentElement.style.colorScheme = 'light';
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('darkMode', true);
          darkBtn && darkBtn.classList.add('active');
          lightBtn && lightBtn.classList.remove('active');
          document.documentElement.style.colorScheme = 'dark';
        }
      });

    darkBtn &&
      darkBtn.addEventListener('click', () => {
        loadImg();
        if (document.documentElement.classList.contains('dark')) {
          return;
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('darkMode', true);
          darkBtn.classList.add('active');
          lightBtn.classList.remove('active');
          document.documentElement.style.colorScheme = 'dark';
        }
      });

    lightBtn &&
      lightBtn.addEventListener('click', () => {
        loadImg();
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('darkMode', false);
          lightBtn.classList.add('active');
          darkBtn.classList.remove('active');
          document.documentElement.style.colorScheme = 'light';
        }
      });

    // Sidebar show and hide

    const sidebar = document.getElementById('sidebar');
    const topbar = document.getElementById('topbar');
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const mainContent = document.querySelector('.main-content');
    if (sidebar) {
      if (window.innerWidth > 1200) {
        sidebar.classList.remove('sidebarhide');
        sidebar.classList.add('sidebarshow');
        mainContent.classList.add('has-sidebar');
      } else {
        sidebar.classList.remove('sidebarshow');
        sidebar.classList.add('sidebarhide');
        mainContent.classList.remove('has-sidebar');
      }
      // show hide sidebar on resize
      window.addEventListener('resize', () => {
        if (window.innerWidth > 1200) {
          sidebar.classList.remove('sidebarhide');
          sidebar.classList.add('sidebarshow');
          mainContent.classList.add('has-sidebar');
          topbar.classList.remove('topbarfull');
          topbar.classList.add('topbarmargin');
        } else {
          sidebar.classList.remove('sidebarshow');
          sidebar.classList.add('sidebarhide');
          mainContent.classList.remove('has-sidebar');
          topbar.classList.add('topbarfull');
          topbar.classList.remove('topbarmargin');
        }
      });

      if (sidebar.classList.contains('sidebarshow')) {
        topbar.classList.remove('topbarfull');
        topbar.classList.add('topbarmargin');
      }

      sidebarToggleBtn.addEventListener('click', () => {
        if (sidebar.classList.contains('sidebarshow')) {
          sidebar.classList.remove('sidebarshow');
          sidebar.classList.add('sidebarhide');
          topbar.classList.remove('topbarmargin');
          mainContent.classList.remove('has-sidebar');
          topbar.classList.add('topbarfull');
          if (window.innerWidth < 1400) {
            document.addEventListener('click', closeSidebarOutside);
          }
        } else {
          sidebar.classList.remove('sidebarhide');
          sidebar.classList.add('sidebarshow');
          mainContent.classList.add('has-sidebar');
          topbar.classList.remove('topbarfull');
          topbar.classList.add('topbarmargin');
          if (window.innerWidth < 1400) {
            document.addEventListener('click', closeSidebarOutside);
          }
        }
      });
      sidebarCloseBtn.addEventListener('click', () => {
        if (sidebar.classList.contains('sidebarshow')) {
          sidebar.classList.remove('sidebarshow');
          sidebar.classList.add('sidebarhide');
        }
      });

      function closeSidebarOutside(event) {
        const isClickedInsideSidebar = sidebar.contains(event.target);
        const isClickedOnSidebarBtn = sidebarToggleBtn.contains(event.target);

        if (!isClickedInsideSidebar && !isClickedOnSidebarBtn) {
          sidebar.classList.add('sidebarhide');
          sidebar.classList.remove('sidebarshow');
          document.removeEventListener('click', closeSidebarOutside);
        }
      }
    }
    document.body.classList.remove('hidden');
  });

  // Reusable function for show/hide dropdown
  function toggleDropdown(btnId, dropdownId) {
    const dropdownBtn = document.getElementById(btnId);
    const dropdown = document.getElementById(dropdownId);

    if (dropdown.classList.contains('hide')) {
      dropdown.classList.remove('hide');
      dropdown.classList.add('show');
      document.addEventListener('click', closeDropdownOutside);
    } else {
      dropdown.classList.add('hide');
      dropdown.classList.remove('show');
      document.removeEventListener('click', closeDropdownOutside);
    }

    function closeDropdownOutside(event) {
      const isClickedInsideDropdown = dropdown.contains(event.target);
      const isClickedOnDropdownBtn = dropdownBtn.contains(event.target);

      if (!isClickedInsideDropdown && !isClickedOnDropdownBtn) {
        dropdown.classList.add('hide');
        dropdown.classList.remove('show');
        document.removeEventListener('click', closeDropdownOutside);
      }
      const arrow = dropdownBtn.querySelector('#drop-arrow');
      if (arrow) {
        if (dropdown.classList.contains('show')) {
          arrow.classList.add('rotate-180');
        } else {
          arrow.classList.remove('rotate-180');
        }
      }
    }
  }

  notificationBtn && notificationBtn.addEventListener('click', () => toggleDropdown('notification-btn', 'notification'));
  profileBtn && profileBtn.addEventListener('click', () => toggleDropdown('profile-btn', 'profile'));
  profileBtn &&
    mobileSearchBtn.addEventListener('click', () => {
      toggleDropdown('mobile-search-btn', 'mobile-search');
    });
  // Layout swithcer

  layoutBtn &&
    layoutBtn.addEventListener('click', () => {
      toggleDropdown('layout-btn', 'layout');
    });

  if (layoutList) {
    layoutList.forEach((item) => {
      item.addEventListener('click', (event) => {
        // Update the selected layout text
        selectedLayout.textContent = item.textContent;
        // document.body.classList.add = item.innerText;
        currentlayout = item.dataset.layout;
        document.body.classList.add(item.dataset.layout);
        localStorage.setItem('layout', item.dataset.layout);

        changeLayout(currentlayout);
        item.classList.add('active');
        // Close the dropdown
        toggleDropdown('layout-btn', 'layout');
        if (layout.classList.contains('show')) {
          document.getElementById('drop-arrow').classList.add('rotate-180');
        } else {
          document.getElementById('drop-arrow').classList.remove('rotate-180');
        }
        // Prevent the click event from propagating to the document
        event.stopPropagation();
      });
    });
  }

  // Language switcher

  languageBtn && languageBtn.addEventListener('click', () => toggleDropdown('language-btn', 'language'));

  if (languageList) {
    languageList.forEach((item) => {
      item.addEventListener('click', (event) => {
        // Remove "active" class from all elements
        languageList.forEach((otherItem) => {
          otherItem.classList.remove('active');
        });
        // Add "active" class to the clicked element
        item.classList.add('active');
        // Close the dropdown
        toggleDropdown('language-btn', 'language');
        // Prevent the click event from propagating to the document
        event.stopPropagation();
      });
    });
  }

  menuBtns.forEach((button) => {
    button.addEventListener('click', () => {
      const submenu = button.nextElementSibling;

      // Close all other submenus
      submenus.forEach((otherSubmenu) => {
        if (otherSubmenu !== submenu) {
          otherSubmenu.classList.add('submenu-hide');
          otherSubmenu.classList.remove('submenu-show');
          // You might also want to remove the "active" class from corresponding buttons
          const correspondingButton = otherSubmenu.previousElementSibling;
          if (correspondingButton) {
            correspondingButton.classList.remove('active');
          }
        }
      });

      if (!document.body.classList.contains('two-column')) {
        // Toggle the clicked submenu
        if (submenu.classList.contains('submenu-hide')) {
          submenu.classList.remove('submenu-hide');
          submenu.classList.add('submenu-show');
          button.classList.add('active');
        } else {
          submenu.classList.add('submenu-hide');
          submenu.classList.remove('submenu-show');
          button.classList.remove('active');
        }
      } else {
        submenu.classList.add('submenu-show');
        submenu.classList.remove('submenu-hide');
        button.classList.add('active');
      }
    });
  });

  // customizer show hide
  const customizerBtn = document.getElementById('customizer-btn');
  const customizerCloseBtn = document.getElementById('customizer-close-btn');
  const customizer = document.getElementById('customizer');
  const customizerWrapper = document.getElementById('customizer-wrapper');

  customizerCloseBtn &&
    customizerCloseBtn.addEventListener('click', () => {
      customizer.classList.add('customizer-hide');
      customizer.classList.remove('customizer-show');
      customizerWrapper.classList.remove('customizer-wrapper');
    });

  customizerBtn &&
    customizerBtn.addEventListener('click', () => {
      if (customizer.classList.contains('customizer-hide')) {
        customizer.classList.remove('customizer-hide');
        customizer.classList.add('customizer-show');
        customizerWrapper.classList.add('customizer-wrapper');
        document.addEventListener('click', closeDropdownOutside);
      } else {
        customizer.classList.add('customizer-hide');
        customizer.classList.remove('customizer-show');
        customizerWrapper.classList.remove('customizer-wrapper');
        document.removeEventListener('click', closeDropdownOutside);
      }

      function closeDropdownOutside(event) {
        const isClickedInsideDropdown = customizer.contains(event.target);
        const isClickedOnDropdownBtn = customizerBtn.contains(event.target);

        if (!isClickedInsideDropdown && !isClickedOnDropdownBtn) {
          customizer.classList.add('customizer-hide');
          customizer.classList.remove('customizer-show');
          customizerWrapper.classList.remove('customizer-wrapper');
          document.removeEventListener('click', closeDropdownOutside);
        }
      }
    });

  const layoutBtns = document.querySelectorAll('.layout-btn');
  if (layoutBtns) {
    layoutBtns.forEach((button) => {
      button.addEventListener('click', () => {
        layoutBtns.forEach((btn) => {
          btn.classList.remove('active');
        });
        button.classList.add('active');
        currentlayout = button.textContent.trim().toLowerCase();
        selectedLayout.textContent = currentlayout;
        localStorage.setItem('layout', currentlayout);
        document.body.classList.add(currentlayout);
        changeLayout(currentlayout);
      });
    });
  }

  // open account modal
  function setupModal(modalBtnSelector, modalOverlaySelector, modalCloseBtnSelector) {
    const modalBtn = document.querySelector(modalBtnSelector);
    const modalOverlay = document.querySelector(modalOverlaySelector);
    const modalCloseBtn = document.querySelector(modalCloseBtnSelector);

    modalBtn.addEventListener('click', () => {
      modalOverlay.classList.remove('modalhide');
      modalOverlay.classList.add('modalshow');
    });

    modalCloseBtn.addEventListener('click', () => {
      modalOverlay.classList.add('modalhide');
      modalOverlay.classList.remove('modalshow');
    });

    // Close modal when clicking outside modalInner
    modalOverlay.addEventListener('click', (event) => {
      if (event.target === modalOverlay) {
        modalOverlay.classList.add('modalhide');
        modalOverlay.classList.remove('modalshow');
      }
    });
  }

  function setupModalMultiple(modalBtnSelector, modalOverlaySelector, modalCloseBtnSelector) {
    const modalBtns = document.querySelectorAll(modalBtnSelector);
    const modalOverlay = document.querySelector(modalOverlaySelector);
    const modalCloseBtn = document.querySelector(modalCloseBtnSelector);

    modalBtns.forEach((modalBtn) => {
      modalBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('modalhide');
        modalOverlay.classList.add('modalshow');
      });
    });

    modalCloseBtn.addEventListener('click', () => {
      modalOverlay.classList.add('modalhide');
      modalOverlay.classList.remove('modalshow');
    });

    // Close modal when clicking outside modalInner
    modalOverlay.addEventListener('click', (event) => {
      if (event.target === modalOverlay) {
        modalOverlay.classList.add('modalhide');
        modalOverlay.classList.remove('modalshow');
      }
    });
  }

  // Example usage for the first modal
  if (document.querySelector('.ac-modal-btn')) {
    setupModal('.ac-modal-btn', '.ac-modal-overlay', '.ac-modal-close-btn');
  }
  if (document.querySelector('.add-account-btn')) {
    setupModal('.add-account-btn', '.modal-two-overlay', '.modal-two-close-btn');
  }
  if (document.querySelector('.total-deposit-btn')) {
    setupModal('.total-deposit-btn', '.modal-two-overlay', '.modal-two-close-btn');
  }
  // Add New card Modal
  if (document.querySelector('.add-card-btn')) {
    setupModal('.add-card-btn', '.add-card-modal', '.add-card-modal-close-btn');
  }
  // transaction modal btn
  if (document.querySelector('.tn-modal-btn')) {
    setupModalMultiple('.tn-modal-btn', '.tn-modal', '.tn-modal-close-btn');
  }
  // QT Modal Btn
  if (document.querySelector('.qt-modal-btn')) {
    setupModalMultiple('.qt-modal-btn', '.qt-modal', '.qt-modal-close-btn');
  }

  // dropdown menu three dots
  // document.addEventListener('click', function (event) {
  //   // Find all dropdown buttons and their corresponding dropdowns
  //   var dropdownButtons = document.querySelectorAll('.horiz-option-btn');
  //   var dropdowns = document.querySelectorAll('.horiz-option');

  //   // Close all dropdowns except the one clicked
  //   dropdowns.forEach(function (drop) {
  //     if (!event.target.closest('.horiz-option') && drop !== event.target.nextElementSibling) {
  //       drop.classList.add('hide');
  //       drop.classList.remove('show');
  //     }
  //   });

  //   // Toggle the visibility of the clicked dropdown
  //   dropdownButtons.forEach(function (button) {
  //     var dropdown = button.nextElementSibling;
  //     if (button === event.target || button.contains(event.target)) {
  //       if (dropdown.classList.contains('hide')) {
  //         dropdown.classList.remove('hide');
  //         dropdown.classList.add('show');
  //       } else {
  //         dropdown.classList.add('hide');
  //         dropdown.classList.remove('show');
  //       }
  //     }
  //   });
  // });

  // slider
  const walletSliderEl = document.querySelector('.walletSwiper');
  if (walletSliderEl) {
    var walletSlider = new Swiper('.walletSwiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      freeMode: true,
      loop: true,
      rtl: document.documentElement.getAttribute('dir') == 'rtl',
      speed: 1500,
      autoplay: {
        delay: 1400,
      },
      navigation: {
        prevEl: '.prev-wallet',
        nextEl: '.next-wallet',
      },
    });
  }
  // check all
  document.addEventListener('DOMContentLoaded', function () {
    // Get the "Select All" checkbox and all individual checkboxes
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
      const checkboxes = document.querySelectorAll(".select-all-table tbody input[type='checkbox']");

      // Add a change event listener to the "Select All" checkbox
      selectAllCheckbox.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
          checkbox.checked = selectAllCheckbox.checked;
        });
      });

      // Add change event listeners to individual checkboxes
      checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
          // If any individual checkbox is unchecked, uncheck the "Select All" checkbox
          if (!checkbox.checked) {
            selectAllCheckbox.checked = false;
          }

          // Check if all individual checkboxes are checked and update "Select All" accordingly
          var allChecked = Array.from(checkboxes).every(function (cb) {
            return cb.checked;
          });

          selectAllCheckbox.checked = allChecked;
        });
      });
    }
  });

  // nice select
  if (document.querySelector('.nc-select')) {
    const selects = document.querySelectorAll('.nc-select');
    selects.forEach((el) => {
      NiceSelect.bind(el);
    });
  }
  // Show current Year on footer
  const yearEl = document.getElementById('current-year');
  if (yearEl) {
    yearEl.innerText = new Date().getFullYear();
  }

  // datatables
  // Transaction style 01 table
  // if (document.getElementById("transactionTable1")) {
  //   const transactionTable = new simpleDatatables.DataTable(
  //     "#transactionTable1",
  //     {
  //       searchable: true,
  //       perPage: 12,
  //       perPageSelect: false,
  //     }
  //   );
  //   const transactionSearchInput = document.getElementById("transaction-search");
  //   transactionSearchInput &&
  //     transactionSearchInput.addEventListener("input", function () {
  //       const searchTerm = this.value.toLowerCase().trim();
  //       transactionTable.search(searchTerm);
  //     });
  // }

  // provider btn
  function setActiveTab(event, tab) {
    const providerBtns = document.querySelectorAll('.provider-btn');
    const providerTabs = document.querySelectorAll('.provider-tab');

    providerBtns.forEach((btn) => {
      btn.classList.remove('provider-active');
    });
    providerTabs.forEach((tab) => {
      tab.classList.remove('tab-active');
    });
    document.getElementById(tab).classList.add('tab-active');
    event.currentTarget.classList.add('provider-active');
  }

  // datetime
  const datetimeEl = document.querySelector('.date-time');
  if (datetimeEl) {
    datetimeEl.textContent = new Date().toLocaleString();
  }

  // datepicker
  const date = document.getElementById('date');
  const date2 = document.getElementById('date2');
  if (date) {
    const picker = datepicker('#date');
    const calendarIcon = document.querySelector('.la-calendar');
    calendarIcon.addEventListener('click', () => {
      picker.show();
    });
  }
  if (date2) {
    const picker = datepicker('#date2');
  }

  function togglePassword(passwordId, clickedIcon) {
    var passwordInput = document.getElementById(passwordId);
    var eyeIcons = clickedIcon.parentNode.querySelectorAll('i');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcons[0].style.display = 'block';
      eyeIcons[1].style.display = 'none';
    } else {
      passwordInput.type = 'password';
      eyeIcons[0].style.display = 'none';
      eyeIcons[1].style.display = 'block';
    }
  }

  // Custom checkbox switch
  const checkboxes = document.querySelectorAll('.custom-checkbox');

  checkboxes.forEach(function (checkbox) {
    const dot = checkbox.parentElement.querySelector('.dot');
    const bgDiv = checkbox.parentElement.querySelector('.bg');

    checkbox.addEventListener('change', function () {
      if (checkbox.checked) {
        dot.classList.add('translate-x-full');
        bgDiv.classList.add('bg-primary');
        bgDiv.classList.remove('bg-primary/20');
      } else {
        dot.classList.remove('translate-x-full');
        bgDiv.classList.remove('bg-primary');
        bgDiv.classList.add('bg-primary/20');
      }
    });
  });

  // Custom Slider
  class Slider {
    constructor(rangeElement, valueElement, options) {
      this.rangeElement = rangeElement;
      this.valueElement = valueElement;
      this.options = options;

      // Attach a listener to "input" event
      this.rangeElement.addEventListener('input', this.updateSlider.bind(this));
    }

    // Initialize the slider
    init() {
      this.rangeElement.setAttribute('min', this.options.min);
      this.rangeElement.setAttribute('max', this.options.max);
      this.rangeElement.value = this.options.cur;

      this.updateSlider();
    }

    // Format the money
    asMoney(value) {
      // Format the value as money with maximumFractionDigits set to 2
      let formattedValue = parseFloat(value).toLocaleString('en-US', {
        maximumFractionDigits: 2,
      });
      // Append ".00" if the formatted value does not already contain decimal places
      if (!formattedValue.includes('.')) {
        formattedValue += '.00';
      }
      return '$' + formattedValue;
    }

    generateBackground(rangeElement) {
      if (this.rangeElement.value === this.options.min) {
        return;
      }

      let percentage = ((this.rangeElement.value - this.options.min) / (this.options.max - this.options.min)) * 100;
      return 'background: linear-gradient(to right, #20B757, #20B757 ' + percentage + '%, #d3edff ' + percentage + '%, #dee1e2 100%)';
    }

    updateSlider(newValue) {
      this.valueElement.innerHTML = this.asMoney(this.rangeElement.value);
      this.rangeElement.style = this.generateBackground(this.rangeElement.value);
    }
  }

  // Initialize multiple sliders
  const sliders = document.querySelectorAll(".range [type='range']");
  const valueElements = document.querySelectorAll('.range .range__value');

  const options = { min: 1, max: 100, cur: 30 };

  if (sliders.length === valueElements.length) {
    sliders.forEach((slider, index) => {
      const sliderInstance = new Slider(slider, valueElements[index], options);
      sliderInstance.init();
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    // preloader
    const loader = document.querySelector('.loader');
    if (loader) {
      setTimeout(() => {
        loader.style.display = 'none';
      }, 300);
    }
  });

  // invoice active
  const invoiceBtnContainer = document.querySelector('.invoice-btns');
  if (invoiceBtnContainer) {
    const btns = invoiceBtnContainer.querySelectorAll('button');
    btns.forEach((btn) => {
      btn.addEventListener('click', () => {
        btns.forEach((btn) => {
          btn.classList.remove('invoice-active');
        });
        btn.classList.add('invoice-active');
      });
    });
  }

  // chat show hide
  const chatBtn = document.querySelector('.chatbtn');
  const chatSidebar = document.getElementById('chat-sidebar');
  if (chatBtn) {
    chatBtn.addEventListener('click', () => {
      if (chatSidebar.classList.contains('chathide')) {
        chatSidebar.classList.add('chatshow');
        chatSidebar.classList.remove('chathide');
      } else {
        chatSidebar.classList.remove('chatshow');
        chatSidebar.classList.add('chathide');
      }
    });
  }

  // quick transfer toggle order
  const spend = document.querySelector('.spend');
  const receive = document.querySelector('.receive');
  const changeOrderBtn = document.querySelector('.changeOrderBtn');
  if (changeOrderBtn) {
    changeOrderBtn.addEventListener('click', () => {
      if (spend.classList.contains('order-1')) {
        spend.classList.remove('order-1');
        spend.classList.add('order-3');
        receive.classList.remove('order-3');
        receive.classList.add('order-1');
      } else {
        spend.classList.add('order-1');
        spend.classList.remove('order-3');
        receive.classList.add('order-3');
        receive.classList.remove('order-1');
      }
    });
  }

  // quill editor
  if (document.getElementById('editor')) {
    var quill = new Quill('#editor', {
      theme: 'snow',
    });
  }

  // form validation
  function validateLoginForm() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password2').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let isValid = true; // Flag to track overall validity

    // Array to store error messages
    const errors = [];

    if (email.trim() === '') {
      errors.push({ field: 'email', message: 'Email is required.' });
      isValid = false;
    } else if (!emailRegex.test(email)) {
      errors.push({ field: 'email', message: 'Invalid email format.' });
      isValid = false;
    } else {
      removeError('email');
    }

    if (password.trim() === '') {
      errors.push({ field: 'passwordfield', message: 'Password is required.' });
      isValid = false;
    } else if (password.trim().length < 4) {
      errors.push({
        field: 'passwordfield',
        message: 'Password should at least 4 character long.',
      });
      isValid = false;
    } else {
      removeError('passwordfield');
    }

    // Display all errors
    errors.forEach((error) => {
      displayError(error.field, error.message);
    });

    // Remove errors if fields are valid

    return isValid;
  }
  function validateSignupForm() {
    const fname = document.getElementById('fname').value;
    const lname = document.getElementById('lname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password2').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let isValid = true; // Flag to track overall validity

    // Array to store error messages
    const errors = [];

    if (fname.trim() === '') {
      errors.push({ field: 'fname', message: 'First Name is required.' });
      isValid = false;
    } else {
      removeError('fname');
    }
    if (lname.trim() === '') {
      errors.push({ field: 'lname', message: 'Last Name is required.' });
      isValid = false;
    } else {
      removeError('lname');
    }

    if (email.trim() === '') {
      errors.push({ field: 'email', message: 'Email is required.' });
      isValid = false;
    } else if (!emailRegex.test(email)) {
      errors.push({ field: 'email', message: 'Invalid email format.' });
      isValid = false;
    } else {
      removeError('email');
    }

    if (password.trim() === '') {
      errors.push({ field: 'passwordfield', message: 'Password is required.' });
      isValid = false;
    } else if (password.trim().length < 4) {
      errors.push({
        field: 'passwordfield',
        message: 'Password should at least 4 character long.',
      });
      isValid = false;
    } else {
      removeError('passwordfield');
    }

    // Display all errors
    errors.forEach((error) => {
      displayError(error.field, error.message);
    });

    return isValid;
  }

  function displayError(fieldId, errorMessage) {
    const field = document.getElementById(fieldId);
    field.classList.add('border-red-500');

    // Check if error message already exists
    const parentElement = field.parentElement;
    let existingError = parentElement.querySelector('.text-red-500');
    if (!existingError) {
      const errorElement = document.createElement('div');
      errorElement.classList.add('text-red-500', 'text-sm', 'mt-1');
      errorElement.textContent = errorMessage;
      parentElement.appendChild(errorElement);
    } else {
      existingError.textContent = errorMessage; // Update existing error message
    }
  }

  function removeError(fieldId) {
    const field = document.getElementById(fieldId);
    field.classList.remove('border-red-500');
    const parentElement = field.parentElement;
    const errorElement = parentElement.querySelector('.text-red-500');
    if (errorElement) {
      parentElement.removeChild(errorElement);
    }
  }
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  loginForm &&
    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (validateLoginForm()) {
        // Form is valid, proceed with submission
        this.submit();
      }
    });
  signupForm &&
    signupForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (validateSignupForm()) {
        // Form is valid, proceed with submission
        this.submit();
      }
    });

  // code highlight
  if (document.querySelector('.code')) {
    hljs.highlightAll();
  }

  // offcanvas
  class BaseComponent {
    constructor(id, options = {}) {
      this.id = id;
      this.element = document.getElementById(id);
      this.options = options;
      this.overlay = null;
      this.isOpen = false;
    }

    createOverlay() {
      this.overlay = document.createElement('div');
      this.overlay.className = 'fixed inset-0 bg-black opacity-50 z-40 hidden transition-opacity duration-300';
      document.body.appendChild(this.overlay);
    }

    addEventListeners() {
      document.querySelectorAll(`[data-${this.componentType}-target="${this.id}"]`).forEach((button) => {
        button.addEventListener('click', () => this.open());
      });

      this.element.querySelectorAll(`[data-${this.componentType}-close="${this.id}"]`).forEach((button) => {
        button.addEventListener('click', () => this.close());
      });

      this.overlay.addEventListener('click', () => this.close());
    }

    open() {
      if (this.isOpen) return;
      this.isOpen = true;
      this.element.classList.remove('hidden');
      this.overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    moveToBody() {
      document.body.appendChild(this.element);
    }
    close() {
      if (!this.isOpen) return;
      this.isOpen = false;
      this.element.classList.add('hidden');
      this.overlay.classList.add('hidden');
      document.body.style.overflow = ''; // Re-enable scrolling
    }
  }

  class OffCanvas extends BaseComponent {
    constructor(id, options = {}) {
      super(id, {
        position: 'left',
        width: '16rem',
        ...options,
      });
      this.componentType = 'offcanvas';
      this.init();
    }

    init() {
      this.createOverlay();
      this.applyStyles();
      this.addEventListeners();
      this.moveToBody();
    }

    applyStyles() {
      this.element.style.width = this.options.width;
      if (this.options.position === 'right') {
        this.element.classList.remove('left-0', '-translate-x-full');
        this.element.classList.add('right-0', 'translate-x-full');
      }
    }

    open() {
      super.open();
      this.element.classList.remove(this.options.position === 'left' ? '-translate-x-full' : 'translate-x-full');
    }

    close() {
      super.close();
      this.element.classList.add(this.options.position === 'left' ? '-translate-x-full' : 'translate-x-full');
    }
  }

  class Modal extends BaseComponent {
    constructor(id, options = {}) {
      super(id, options);
      this.componentType = 'modal';
      this.init();
    }

    init() {
      this.createOverlay();
      this.addEventListeners();
      this.moveToBody();
    }
  }

  // tabs.js

  class Tabs {
    constructor(element) {
      this.tabLinks = element.querySelectorAll('.tab-link');
      this.tabPanels = element.querySelectorAll('.tab-panel');

      // Error Handling: Check if we have the necessary elements
      if (!this.tabLinks.length) {
        console.warn('No tab links found for this tab group. Skipping initialization.');
        return;
      }
      if (!this.tabPanels.length) {
        console.warn('No tab panels found for this tab group. Skipping initialization.');
        return;
      }
      if (this.tabLinks.length !== this.tabPanels.length) {
        console.warn('The number of tab links does not match the number of tab panels.');
      }

      this.init();
    }

    init() {
      // Add event listeners to all tab links
      this.tabLinks.forEach((link, index) => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          this.switchTab(index); // Pass the index of the clicked tab
        });
      });
    }

    switchTab(index) {
      // Remove active state from all tabs
      this.tabLinks.forEach((link) => {
        link.classList.remove('active');
      });

      // Hide all panels
      this.tabPanels.forEach((panel) => {
        panel.classList.add('hidden');
      });

      // Activate the clicked tab and corresponding panel
      if (this.tabLinks[index]) {
        this.tabLinks[index].classList.add('active');
      }
      if (this.tabPanels[index]) {
        this.tabPanels[index].classList.remove('hidden');
      }
    }
  }

  // dropdown
  class Dropdown {
    constructor(element) {
      this.toggleButton = element.querySelector('.dropdown-toggle');
      this.menu = element.querySelector('.dropdown-menu');

      // Error Handling: Check if required elements exist
      if (!this.toggleButton) {
        console.warn('Dropdown toggle button not found.');
        return;
      }
      if (!this.menu) {
        console.warn('Dropdown menu not found.');
        return;
      }

      // Initialize event listener for the dropdown toggle
      this.init();
    }

    init() {
      // Toggle the dropdown menu when the button is clicked
      this.toggleButton.addEventListener('click', (e) => {
        e.preventDefault();
        this.toggleDropdown();
      });

      // Close the dropdown if clicked outside
      document.addEventListener('click', (e) => {
        if (!this.menu.contains(e.target) && !this.toggleButton.contains(e.target)) {
          this.closeDropdown();
        }
      });

      // Close dropdown when a menu item is clicked
      const items = this.menu.querySelectorAll('.dropdown-item');
      items.forEach((item) => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          this.closeDropdown();
        });
      });
    }

    toggleDropdown() {
      // Toggle between hidden and visible states
      if (this.menu.classList.contains('hidden')) {
        this.openDropdown();
      } else {
        this.closeDropdown();
      }
    }

    openDropdown() {
      this.menu.classList.remove('hidden');
    }

    closeDropdown() {
      this.menu.classList.add('hidden');
    }
  }

  // Initialize all dropdowns on the page

  // accordion
  class Accordion {
    constructor(accordion) {
      try {
        this.accordion = accordion;
        if (!this.accordion) {
          throw new Error(`Accordion with class "${accordion}" not found`);
        }
        this.items = this.accordion.querySelectorAll('.accordion-item');
        if (this.items.length === 0) {
          throw new Error(`No accordion items found in accordion with class "${accordion}"`);
        }
        this.init();
      } catch (error) {
        console.error('Accordion initialization error:', error.message);
      }
    }

    init() {
      this.items.forEach((item, index) => {
        const header = item.querySelector('.accordion-header');
        const content = item.querySelector('.accordion-content');
        const arrow = item.querySelector('.accordion-arrow');

        if (!header || !content || !arrow) {
          console.warn(`Accordion item ${index} is missing required elements`);
          return;
        }

        // Set initial state
        content.style.maxHeight = index === 0 ? `${content.scrollHeight}px` : '0px';
        item.classList.toggle('active', index === 0);
        arrow.style.transform = index === 0 ? 'rotate(180deg)' : 'rotate(0deg)';

        header.addEventListener('click', () => this.toggleItem(item));
      });
    }

    toggleItem(item) {
      const content = item.querySelector('.accordion-content');
      const arrow = item.querySelector('.accordion-arrow');
      const isActive = item.classList.contains('active');

      // Close all items
      this.items.forEach((i) => {
        const itemContent = i.querySelector('.accordion-content');
        const itemArrow = i.querySelector('.accordion-arrow');
        i.classList.remove('active');
        if (itemContent) itemContent.style.maxHeight = '0px';
        if (itemArrow) itemArrow.style.transform = 'rotate(0deg)';
      });

      // Open clicked item if it was closed
      if (!isActive) {
        item.classList.add('active');
        if (content) content.style.maxHeight = `${content.scrollHeight}px`;
        if (arrow) arrow.style.transform = 'rotate(180deg)';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Initialize all dropdowns on the page
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach((dropdownElement) => {
      new Dropdown(dropdownElement);
    });

    // Initialize all tabs on the page
    const tabGroups = document.querySelectorAll('.tabs');
    tabGroups.forEach((tabGroup) => {
      new Tabs(tabGroup);
    });

    // Initialize off-canvas instances
    document.querySelectorAll('[data-offcanvas-target]').forEach((el) => {
      const position = el.getAttribute('data-offcanvas-position') || 'left';
      const width = el.getAttribute('data-offcanvas-width') || '20rem';
      new OffCanvas(el.getAttribute('data-offcanvas-target'), { position, width });
    });
    // initialize modals
    document.querySelectorAll('[data-modal-target]').forEach((el) => {
      new Modal(el.getAttribute('data-modal-target'));
    });

    // initialize accordions
    if (document.querySelector('.accordion')) {
      document.querySelectorAll('.accordion').forEach((el) => {
        new Accordion(el);
      });
    }
  });

  // copy code

  const copyButtons = document.querySelectorAll('.copy-code');
  copyButtons.length &&
    copyButtons.forEach((button) => {
      button.addEventListener('click', async () => {
        // Find the closest pre element
        const preElement = button.nextElementSibling;

        if (preElement) {
          // Get the text content of the code element inside pre
          try {
            // Use the Clipboard API to copy the text
            await navigator.clipboard.writeText(preElement.innerText);

            // Provide visual feedback (optional)
            const btnText = button.querySelector('.btn-text');
            btnText.textContent = 'Copied!';
            setTimeout(() => {
              btnText.textContent = 'Copy';
            }, 2000);
          } catch (err) {
            console.error('Failed to copy text: ', err);
          }
        }
      });
    });

  // notyf
  const toasts = document.querySelectorAll('[data-toast]');
  if (toasts.length) {
    var notyf = new Notyf({ position: { y: 'top' } });
    toasts.forEach((toast) => {
      const type = toast.getAttribute('data-toast') || 'success';
      const message = toast.getAttribute('data-message') || 'This is your message';
      toast.addEventListener('click', () => {
        if (type === 'success') {
          notyf.success(message);
        } else if (type === 'error') {
          notyf.error(message);
        }
      });
    });
  }

  // tippy js

  document.addEventListener('DOMContentLoaded', () => {
    // Select all buttons with the 'popover-button' class
    const buttons = document.querySelectorAll('.popover-button');
    if (buttons.length) {
      // Initialize Tippy for each button
      buttons.forEach((button) => {
        const content = button.nextElementSibling;
        tippy(button, {
          content: content,
          allowHTML: true,
          interactive: true,
          trigger: 'click',
          placement: 'left',
          animation: 'scale',
          arrow: false,
          appendTo: document.body,
          onShow(instance) {
            // Ensure content is visible when popover is shown
            instance.popper.querySelector('.popover-content').style.display = 'block';

               // Close popover when a <li> item is clicked
               const listItems = instance.popper.querySelectorAll('.popover-content li');
               listItems.forEach((item) => {
                 item.addEventListener('click', () => {
                   instance.hide(); // Hide the popover when item is clicked
                 });
               });
          },
          onHide(instance) {
            // Hide content when popover is hidden
            instance.popper.querySelector('.popover-content').style.display = 'none';
          },
        });
      });
    }
  });

const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.querySelector('.profile-side-bar');
const overlay = document.getElementById('overlay');
const closeIcon = document.querySelector('.close-icon');

menuToggle.addEventListener('click', function () {
    sidebar.classList.toggle('show');
    overlay.classList.toggle('active');
});
closeIcon.addEventListener('click', function () {
    sidebar.classList.remove('show');
    overlay.classList.remove('active');
});
overlay.addEventListener('click', function () {
    sidebar.classList.remove('show');
    overlay.classList.remove('active');
});

document.addEventListener("DOMContentLoaded", function () {
    /* ============================ INDEX TAB ============================ */
    const informationTab = document.querySelector(".information-tab-action");
    const loveTab = document.querySelector(".love-tab");
    const changePasswordTab = document.querySelector(".change-password-tab-action");
    const menuItems = document.querySelectorAll(".profile-side-bar li");

    // Icon paths
    const icons = [
        {
            normal: userIcon,
            active: userIconActive,
            hover: userIconHover
        },
        {
            normal: heartIcon,
            active: heartIconActive,
            hover: heartIconHover
        },
        {
            normal: lockIcon,
            active: lockIconActive,
            hover: lockIconHover
        },
        {
            normal: logoutIcon,
            active: logoutIconActive,
            hover: logoutIconHover
        }
    ];

    // Function to activate a tab
    function activateTab(tab) {
        document.querySelectorAll(".profile-tab > *").forEach(div => div.classList.remove("active-tab"));
        tab.classList.add("active-tab");
    }

    // Function to activate menu item
    function activateMenuItem(index) {
        menuItems.forEach((item, i) => {
            item.classList.remove("active");
            const icon = item.querySelector("img");
            if (icon) {
                icon.src = icons[i].normal;
            }
        });

        menuItems[index].classList.add("active");
        const activeIcon = menuItems[index].querySelector("img");
        if (activeIcon) {
            activeIcon.src = icons[index].active;
        }
    }

    // Set the active tab based on the flash data
    if (activeTab === 'change-password') {
        activateTab(changePasswordTab);
        activateMenuItem(2);
    } else if (activeTab === 'love' || localStorage.getItem('activeTab') === 'love') {
        activateTab(loveTab);
        activateMenuItem(1);
    } else {
        activateTab(informationTab);
        activateMenuItem(0);
    }
    localStorage.removeItem('activeTab');

    // Add click events to the menu items
    menuItems.forEach((item, index) => {
        const icon = item.querySelector("img");

        // Click event
        item.addEventListener("click", function () {
            if (index === 0) activateTab(informationTab);
            else if (index === 1) activateTab(loveTab);
            else if (index === 2) activateTab(changePasswordTab);

            activateMenuItem(index);
        });

        // Hover event
        item.addEventListener("mouseover", function () {
            if (icon && !item.classList.contains("active")) {
                icon.src = icons[index].hover;
            }
        });

        // Mouseout event
        item.addEventListener("mouseout", function () {
            if (icon && !item.classList.contains("active")) {
                icon.src = icons[index].normal;
            }
        });
    });

    /* ============================ INFORMATION TAB ============================ */
    const cameraIcon = document.querySelector(".camera-icon");
    const fileInput = document.querySelector("#upload-avatar");
    const avatarImage = document.querySelector(".information-avatar");

    // Trigger file input when clicking the camera icon
    cameraIcon.addEventListener("click", function () {
        fileInput.click();
    });

    // Handle file input change event
    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    /* ============================ CHANGE PASSWORD TAB ============================ */
    document.querySelectorAll('.show-password').forEach((icon) => {
        icon.addEventListener('click', () => {
            const inputField = icon.previousElementSibling;
            if (inputField.type === 'password') {
                inputField.type = 'text'; // Change to text
            } else {
                inputField.type = 'password'; // Change back to password
            }
        });
    });

    document.getElementById('update-profile-form').addEventListener('submit', function (event) {
        event.preventDefault();
        showLoading();
        setTimeout(() => event.target.submit(), 100);
    });

    window.addEventListener('pageshow', hideLoading);

    document.getElementById('change-password-form').addEventListener('submit', function (event) {
        event.preventDefault();
        showLoading();
        setTimeout(() => event.target.submit(), 100);
    });

    window.addEventListener('pageshow', hideLoading);

    document.getElementById('logout-form').addEventListener('submit', function (event) {
        event.preventDefault();
        showLoading();
        setTimeout(() => event.target.submit(), 100);
    });

    window.addEventListener('pageshow', hideLoading);
});

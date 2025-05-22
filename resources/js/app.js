import './bootstrap';
document.addEventListener("DOMContentLoaded", function () {

    // ОТКРЫТИЕ МЕНЮ ПО НАЖАТИЮ НА АВАТАР ПОЛЬЗОВАТЕЛЯ
    const userName = document.querySelector('.user__wrapper');
    const userMenu = document.querySelector('.dash__menu');

    userName.addEventListener('click', function () {
        if (!userName.classList.contains('active')) {
            openMenu();
        } else {
            closeMenu();
        }
    })

    document.body.addEventListener('click', function (e) {
        if (!userName.contains(e.target) && userName.classList.contains('active')) {
            closeMenu();
        }
    });

    function openMenu() {
        userMenu.style.display = 'flex';
        setTimeout(function () {
            userMenu.style.opacity = 1;
            userMenu.style.top = '4rem';
        }, 100)
        userName.classList.add('active')
    }

    function closeMenu() {
        userMenu.style.opacity = 0;
        userMenu.style.top = '3rem';
        setTimeout(function () {
            userMenu.style.display = 'none';
        }, 100)
        userName.classList.remove('active')
    }

    // ОТКРЫТИЕ И ЗАКРЫТИЕ МОБИЛЬНОГО МЕНЮ
    const menuBtnOpen = document.querySelector('.user-menu__btn');
    const menuBtnClose = document.querySelector('.btn__close');
    const menu = document.querySelector('.user__menu__mobile');

    menuBtnOpen.addEventListener('click', function() {
        menu.style.display = 'flex';
        setTimeout(function() {
            menu.style.right = 0;
        }, 50)
    })

    menuBtnClose.addEventListener('click', function() {
        menu.style.right = '-100vw';
        setTimeout(function () {
            menu.style.display = 'none';
        }, 300)
    })

});
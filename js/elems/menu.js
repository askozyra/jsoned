const MENU_ICON = document.querySelector("#menu-burger");
const MENU_LIST = document.querySelector("#menu-popup");

MENU_ICON.addEventListener("click", event => {
  if(event.target.closest("#menu-popup") === MENU_LIST)
    return;
  
  if(MENU_LIST.classList.contains("menu-popup-show"))
    closeMenu();
  else
    openMenu();
});

function openMenu(){
  MENU_LIST.classList.remove("menu-popup-hide");
  MENU_LIST.classList.add("menu-popup-show");
  
  MENU_ICON.classList.remove("menu-burger-close");
  MENU_ICON.classList.add("menu-burger-open");
}

function closeMenu(){
  MENU_LIST.classList.remove("menu-popup-show");
  MENU_LIST.classList.add("menu-popup-hide");
  
  MENU_ICON.classList.remove("menu-burger-open");
  MENU_ICON.classList.add("menu-burger-close");
}
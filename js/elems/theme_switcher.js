setChosenTheme();

//#region constants
const ITEMS_CONTAINER = document.getElementsByClassName("color_themes_list")[0];
const ITEMS = document.getElementsByClassName("color_theme_item");
//#endregion

//#region event listener
addClickEvent(ITEMS_CONTAINER);
//#endregion

//#region color themes functions
function addClickEvent(item){
  item.addEventListener("click", (event) => {
    const TARGET = event.target;
    const ITEM = getColorThemeItem(ITEMS, TARGET);
  
    switch(TARGET){
      case ITEM:
        const INDEX = getColorThemeIndex(ITEMS, ITEM);
        const COLOR = parseRGB(getColor(ITEM));
  
        switchColorTheme(ITEMS, ITEM);
        changeColorTheme(COLOR[0], COLOR[1], COLOR[2]);
        autoTextColor(COLOR);
        saveCookies(COLOR, INDEX);
        break;
      }
  });  
}

function saveCookies(COLOR, INDEX){
  const COOKIE1 = "theme_color=rgb(" + COLOR[0] + ", " + COLOR[1] + ", " + COLOR[2] + ")";
  const COOKIE2 = "theme_index=" + INDEX;
  
  [COOKIE1, COOKIE2].forEach(el => {
    document.cookie = el + ";  max-age=" + 3600 * 24 * 30 + "; path=/jsoned;";
  });
}

function getColor(item){
  return getComputedStyle(item)["backgroundColor"];
}

function switchColorTheme(items_list, item){
  clearColorThemeSwitch(items_list);
  item.classList.add("color_theme_item-active");
}

function getColorThemeIndex(items_list, item){
  return Array.from(items_list).indexOf(item);
}

function setChosenTheme(){
  const ITEM = document.getElementsByClassName("color_theme_item-active")[0];
  const COLOR = parseRGB(getComputedStyle(ITEM)["backgroundColor"]);
  
  changeColorTheme(COLOR[0], COLOR[1], COLOR[2]);
  autoTextColor(COLOR);
}

function changeColorTheme(r, g, b){
  const ROOT = document.getElementsByTagName("html")[0];
  ROOT.style.setProperty("--main-color-theme", "rgb(" + r + ", " + g + ", " + b + ")");
  ROOT.style.setProperty("--main-color-theme-red", r);
  ROOT.style.setProperty("--main-color-theme-green", g);
  ROOT.style.setProperty("--main-color-theme-blue", b);
}

function autoTextColor(themeColor){
  if(themeColor[0] < 120 && themeColor[1] < 120 && themeColor[2] < 240){
    changeTextColor(238, 238, 238);
  } else {
    changeTextColor(51, 51, 51);
  }
}

function changeTextColor(r, g, b){
  const ROOT = document.getElementsByTagName("html")[0];
  ROOT.style.setProperty("--main-text-color", "rgb(" + r + ", " + g + ", " + b + ")");
}

function clearColorThemeSwitch(THEME_ITEMS){
  Array.from(THEME_ITEMS).forEach(el => {
    el.classList.remove("color_theme_item-active");
  });
}

function parseRGB(string){
  const RGB = string.substring(4, string.length-1)
  .replace(/ /g, '')
  .split(',');
  
  return RGB;
}

function getColorThemeItem(item_list, item){
  const THEME_ARRAY = Array.from(item_list);
  const ITEM_INDEX = THEME_ARRAY.indexOf(item);
  return THEME_ARRAY[ITEM_INDEX];
}
//#endregion
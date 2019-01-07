var html = "<head><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\"</head><div id=\"social-container\"><a title=\"What's this?\" href=\"http://codepen.io/woodwork/pen/rxdLyW\" target=\"_blank\" id=\"tooltip\"><span class=\"fa fa-info-circle\" id=\"info\" title=\"\"></span></a><div id=\"social-links\"></div><p id=\"social-message\"></p></div>";

var style = " #social-container { font-family: Futura, Verdana, Arial, sans-serif; color: rgba(0, 0, 0, 0.7); display: block; position: fixed; z-index: 9999; bottom: 16px; right: 16px; text-align: right; } #social-message { padding: 0 8px 0 0; margin: 6px 0; } #social-container a { color: rgba(0, 0, 0, 0.6); letter-spacing: 8px; font-size: 36px; text-decoration: none; -webkit-transition: all 0.2s; transition: all 0.2s; } #social-container a:hover { color: rgba(0, 0, 0, 0.9); } #social-container #info { font-size: 14px; -webkit-transform: translate(12px, 4px); transform: translate(12px, 4px); } #social-container #tooltip:hover:after { box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); letter-spacing: 0; background: #222; font-size: 14px; border-radius: 5px; color: rgba(255, 255, 255, 0.65); padding: 5px; position: absolute; width: 100px; z-index: 10; right: 15px; top: 20px; content: attr(title); } #social-container #tooltip:hover:before { border: solid; border-color: transparent #222; border-width: 6px 0 6px 6px; content: \"\"; right: 9px; top: 25px; position: absolute; z-index: 10; } #social-container.top { top: 0px; right: 16px; } #social-container.light, #social-container.light a { color: rgba(255, 255, 255, 0.6); -webkit-transition: all 0.2s; transition: all 0.2s; } #social-container.light a:hover { color: rgba(255, 255, 255, 0.9); } #social-container.disco a:active, #social-container.disco a:visited, #social-container.disco a:link { -webkit-animation: none; animation: none; } #social-container.disco a:hover { text-shadow: 0 0 20px rgba(0, 222, 248, 0.3); -webkit-animation: disco 2s linear infinite; animation: disco 2s linear infinite; } @-webkit-keyframes disco { 0%, 50% { color: #2FFF90; } 40%, 90% { color: #00DEF8; } 20%, 70% { color: #F7F445; } 30%, 80% { color: #FC7800; } 10%, 60% { color: #FF0042; } 100% { color: #B400FF; } } @keyframes disco { 0%, 50% { color: #2FFF90; } 40%, 90% { color: #00DEF8; } 20%, 70% { color: #F7F445; } 30%, 80% { color: #FC7800; } 10%, 60% { color: #FF0042; } 100% { color: #B400FF; } }";

function determine(currLink, linksContainer) {
  var site = currLink[0].toLowerCase();
  var user = currLink[1]
  switch (site) {
    case "twitter":
    case "dribbble":
    case "instagram":
    case "github":
      link = "<a href='https://" + site + ".com/" + user + "/' target='_blank' class='fa fa-" + site + "'></a>";
      linksContainer.innerHTML = linksContainer.innerHTML + link;
      break;
    case "codepen":
      link = "<a href='http://codepen.io/" + user + "/' target='_blank' class='fa fa-codepen'></a>";
      linksContainer.innerHTML = linksContainer.innerHTML + link;
      break;
    case "facebook":
      link = "<a href='https://facebook.com/" + user + "/' target='_blank' class='fa fa-facebook-square'></a>";
      linksContainer.innerHTML = linksContainer.innerHTML + link;
      break;
    case "linkedin":
      link = "<a href='https://www.linkedin.com/in/" + user + "/' target='_blank' class='fa fa-linkedin-square'></a>";
      linksContainer.innerHTML = linksContainer.innerHTML + link;
      break;
    case "":
      link = "<a href='http://" + user + "' target='_blank' class='fa fa-globe'></a>";
      linksContainer.innerHTML = linksContainer.innerHTML + link;
      break;

    default:
      alert("Social Links Plugin: Sorry, '" + site + "'" + " wasn't recognised, please check your spelling or request a site to be added.");
  }
}

social = function() {
  var _style = document.createElement("style");
  var _html = document.createElement("div");
  _style.innerHTML = style;
  _html.innerHTML = html;
  document.body.appendChild(_style);
  document.body.appendChild(_html);
  
  var link = '';
  var links = document.getElementById('social-links'),
    socialContainer = document.getElementById('social-container'),
    message = document.getElementById('social-message');

  for (var i = 0; i < arguments.length; i++) {
    var argument = arguments[i];

    if (typeof argument === 'string' || argument instanceof String) {
      if (argument.indexOf('/') === -1) {
        switch (argument) {
          case "light":
          case "top":
          case "disco":
            socialContainer.className += " " + argument;
            break;

          default:
            message.innerHTML = argument;
        }
      } else {
        var decode = argument.split("/");
        determine(decode, links);
      }
    } 
  }
};
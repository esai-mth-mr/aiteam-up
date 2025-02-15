window.onload = () => {
  let id = document.getElementById('iframe-embed').getAttribute("aiteamup_data_id");
  let userHeight = document.getElementById('iframe-embed').getAttribute("height"); 
  let userWidth = document.getElementById('iframe-embed').getAttribute("width"); 

  userHeight = parseFloat(userHeight);
  userWidth = parseFloat(userWidth);
  let stylesheetUrl = "https://aiteamup.com/includes/assets/css/icons.css";

  let linkElement = document.createElement("link");
  linkElement.rel = "stylesheet";
  linkElement.type = "text/css";
  linkElement.href = stylesheetUrl;

  // Append the link element to the head of the document
  document.head.appendChild(linkElement);

  let newScript1 = document.createElement("script");
  newScript1.src =
    "https://aiteamup.com/templates/classic-theme/js/jquery.min.js";
  document.body.appendChild(newScript1);

  setTimeout(() => {
    let iframe = document.createElement("iframe");

    iframe.style.width = "100%";
    iframe.style.height = "100%";

    // height = isNaN(userHeight) ? "600px" : `${userHeight}px`;
    height = isNaN(userHeight) ? "600px" : `${userHeight}px`;
    width = isNaN(userWidth) ? "400px" : `${userWidth}px`;
    
    // iframe.style.width = isNaN(userWidth) ? "100%" : `${userWidth}px`;

    iframe.style.border = "none";

    iframe.src = `https://aiteamup.com/embed-chat/?e=${id}&s=true&i=true&heigt=${height}&width=${width}`;

    let iframeDiv = document.getElementById("iframe-embed");

    iframeDiv.appendChild(iframe);

    let newScript3 = document.createElement("script");
    newScript3.src =
      "https://aiteamup.com/templates/classic-theme/js/custom.js";
    document.body.appendChild(newScript3);
  }, 1000);

  let newScript2 = document.createElement("script");
  newScript2.src =
    "https://aiteamup.com/templates/classic-theme/js/snackbar.js";
  document.body.appendChild(newScript2);
};

document.addEventListener('DOMContentLoaded', () => {
  // Your code here
  let id = document.getElementById('iframe-embed').getAttribute("aiteamup_data_id");
  let userHeight = document.getElementById('iframe-embed').getAttribute("height"); 
  let userWidth = document.getElementById('iframe-embed').getAttribute("width"); 

  userHeight = parseFloat(userHeight);
  userWidth = parseFloat(userWidth);
  let stylesheetUrl = "https://aiteamup.com/includes/assets/css/icons.css";

  let linkElement = document.createElement("link");
  linkElement.rel = "stylesheet";
  linkElement.type = "text/css";
  linkElement.href = stylesheetUrl;

  // Append the link element to the head of the document
  document.head.appendChild(linkElement);

  let newScript1 = document.createElement("script");
  newScript1.src = "https://aiteamup.com/templates/classic-theme/js/jquery.min.js";
  document.body.appendChild(newScript1);

  setTimeout(() => {
      let iframe = document.createElement("iframe");

      iframe.style.width = "100%";
      iframe.style.height = "100%";

      // height = isNaN(userHeight) ? "600px" : `${userHeight}px`;
      height = isNaN(userHeight) ? "600px" : `${userHeight}px`;
      width = isNaN(userWidth) ? "400px" : `${userWidth}px`;

      // iframe.style.width = isNaN(userWidth) ? "100%" : `${userWidth}px`;

      iframe.style.border = "none";

      iframe.src = `https://aiteamup.com/embed-chat/?e=${id}&s=true&i=true&heigt=${height}&width=${width}`;

      let iframeDiv = document.getElementById("iframe-embed");

      iframeDiv.appendChild(iframe);

      let newScript3 = document.createElement("script");
      newScript3.src = "https://aiteamup.com/templates/classic-theme/js/custom.js";
      document.body.appendChild(newScript3);
  }, 1000);

  let newScript2 = document.createElement("script");
  newScript2.src = "https://aiteamup.com/templates/classic-theme/js/snackbar.js";
  document.body.appendChild(newScript2);
});

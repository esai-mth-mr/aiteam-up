let id = document.getElementsByClassName("embed-script")[0].id;

// Make a simple GET request
fetch(`https://aiteamup.com/embed-chat/?e=${id}`)
  // fetch(`http://localhost/aiteamup/embed-chat/?e=${id}`)
  .then((response) => {
    if (!response.ok) {
      throw new Error(`HTTPS error! Status: ${response.status}`);
    }
    return response.text(); // or response.json() if the response is in JSON format
  })
  .then((data) => {
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
      let newDiv = document.createElement("div");

      // Set the HTML content of the new div
      newDiv.innerHTML = data;

      // Append the new div to the document body (or another desired location)
      document.body.appendChild(newDiv);

      let newScript3 = document.createElement("script");
      newScript3.src =
        "https://aiteamup.com/templates/classic-theme/js/custom.js";
      document.body.appendChild(newScript3);
    }, 500);
    let newScript2 = document.createElement("script");
    newScript2.src =
      "https://aiteamup.com/templates/classic-theme/js/snackbar.js";
    document.body.appendChild(newScript2);

    // Create a new div element
  })
  .catch((error) => {
    console.error("Fetch error:", error);
  });

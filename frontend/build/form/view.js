(() => {
  // src/view.js
  document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll(".front-it-form .contact-form");
    forms.forEach((form) => {
      form.addEventListener("submit", async function(e) {
        e.preventDefault();
        const nameInput = form.querySelector('input[name="name"]');
        const emailInput = form.querySelector('input[name="email"]');
        const phoneInput = form.querySelector('input[name="phone"]');
        const messageInput = form.querySelector('textarea[name="message"]');
        const submitButton = form.querySelector('button[type="submit"]');
        const responseDiv = form.querySelector(".response-message") || createResponseDiv(form);
        submitButton.disabled = true;
        submitButton.innerHTML = "Sending...";
        responseDiv.innerHTML = "";
        try {
          const response = await fetch("/wp-json/front-it/v1/submit", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              name: nameInput.value,
              email: emailInput.value,
              phone: phoneInput.value,
              message: messageInput.value
            })
          });
          const data = await response.json();
          if (data.success) {
            responseDiv.innerHTML = `<div class="success-message">${data.message || "Thank you! Your message has been sent successfully."}</div>`;
            e.target.reset();
            form.classList.add("form-hidden");
          } else {
            responseDiv.innerHTML = `<div class="error-message">${data.message || "Something went wrong. Please try again."}</div>`;
          }
        } catch (error) {
          responseDiv.innerHTML = '<div class="error-message">Unable to send message. Please try again later.</div>';
          console.error("Form submission error:", error);
        } finally {
          submitButton.disabled = false;
          submitButton.innerHTML = "Submit";
        }
      });
    });
  });
  function createResponseDiv(form) {
    const div = document.createElement("div");
    div.className = "response-message";
    form.parentElement.appendChild(div);
    return div;
  }
})();

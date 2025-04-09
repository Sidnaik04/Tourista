$(document).ready(function () {
  $("#contactForm").submit(function (event) {
    event.preventDefault();

    let name = $("#name").val().trim();
    let email = $("#email").val().trim();
    let phone = $("#phone").val().trim();
    let packageSelected = $("#package").val();

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let phonePattern = /^[0-9]{10,15}$/;

    if (name.length < 3) {
      alert("Name must be at least 3 characters.");
      return;
    }

    if (!emailPattern.test(email)) {
      alert("Enter a valid email address.");
      return;
    }

    if (phone && !phonePattern.test(phone)) {
      alert("Enter a valid phone number (digits only).");
      return;
    }

    if (!packageSelected) {
      alert("Please select a package.");
      return;
    }

    console.log({
      Name: name,
      Email: email,
      Phone: phone || "Not provided",
      Package: packageSelected
    });

    alert("Form submitted successfully!");
    $("#contactForm")[0].reset();
  });
});

document.addEventListener("DOMContentLoaded", function (event) {
  const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
      nav = document.getElementById(navId),
      bodypd = document.getElementById(bodyId),
      headerpd = document.getElementById(headerId);

    // Validate that all variables exist
    if (toggle && nav && bodypd && headerpd) {
      toggle.addEventListener("click", () => {
        // show navbar
        nav.classList.toggle("show");
        // change icon
        toggle.classList.toggle("bx-x");
        // add padding to body
        bodypd.classList.toggle("body-pd");
        // add padding to header
        headerpd.classList.toggle("body-pd");
      });
    }
  };

  showNavbar("header-toggle", "nav-bar", "body-pd", "header");

  /*===== LINK ACTIVE =====*/
  const linkColor = document.querySelectorAll(".nav_link");

  function colorLink() {
    if (linkColor) {
      linkColor.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
    }
  }
  linkColor.forEach((l) => l.addEventListener("click", colorLink));

  // Your code to run since DOM is loaded and ready
});

function validateField(field) {
  //TODO: all validation in this function
}

$(document).ready(function () {
  $("#loginFormNRT").submit(function (e) {
    var form = $(this);

    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
    }).done(function (data) {
      console.log(data);
    });
  });
  $("#startOfDayForm").submit(function (e) {
    var form = $(this);

    var loadNumber = $("#loadNumber");
    var startTime = $("#startTime");

    var valid = true;

    if (!startTime.val()) {
      document.querySelector("#startTime").classList.add("is-invalid");
      valid = false;
    } else {
      document.querySelector("#startTime").classList.remove("is-invalid");
      document.querySelector("#startTime").classList.add("is-valid");
    }

    if (loadNumber.val().length != 5) {
      document.querySelector("#loadNumber").classList.add("is-invalid");
      valid = false;
    } else {
      document.querySelector("#loadNumber").classList.remove("is-invalid");
      document.querySelector("#loadNumber").classList.add("is-valid");
    }

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
    }

    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
    }).done(function (data) {
      console.log(data);
    });
  });

  $("#loginForm").submit(function (e) {
    e.preventDefault();

    var form = $(this);

    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
    }).done(function (data) {
      console.log(data);
    });
  });

  $("#start").submit(function (e) {
    var form = $(this);
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: "status=READY",
    }).done(function (data) {
      console.log(data);
    });
  });

  $("#leaving").submit(function (e) {
    var form = $(this);
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: "status=LEFT_FACILITY",
    }).done(function (data) {
      console.log(data);
    });
  });

  $("#arrived").submit(function (e) {
    var form = $(this);
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: "status=ARRIVED_AT_FACILITY",
    }).done(function (data) {
      console.log(data);
    });
  });

  $("#arrivalForm").submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    var form = $(this);
    var valid = true;

    var facility = $("#facilityName").val();

    if (!facility) {
      document.querySelector("#facilityName").classList.add("is-invalid");
      valid = false;
    }

    console.log("Facility: " + facility);

    if (facility == "danone") {
      var reason = $("#arrivalStatus").val();
      if (!reason) valid = false;
      if (
        reason == "emptyTrailer" &&
        $("#trailerNumberEmpty").val().length < 4
      ) {
        document
          .querySelector("#trailerNumberEmpty")
          .classList.add("is-invalid");
        valid = false;
      }

      if (reason == "samples" || reason == "chepPallets") {
        if ($("#palletsSamples").val().length < 1) {
          document.querySelector("#palletsSamples").classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#palletsSamples")
            .classList.remove("is-invalid");
          document.querySelector("#palletsSamples").classList.add("is-valid");
        }
        if ($("#trailerNumberSamples").val().length < 1) {
          document
            .querySelector("#trailerNumberSamples")
            .classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#trailerNumberSamples")
            .classList.remove("is-invalid");
          document
            .querySelector("#trailerNumberSamples")
            .classList.add("is-valid");
        }
      }

      if (reason == "refusedLoad") {
        if ($("#orderNumberRefused").val().length < 1) {
          document
            .querySelector("#orderNumberRefused")
            .classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#orderNumberRefused")
            .classList.remove("is-invalid");
          document
            .querySelector("#orderNumberRefused")
            .classList.add("is-valid");
        }
        if ($("#referenceNumberRefused").val().length < 1) {
          document
            .querySelector("#referenceNumberRefused")
            .classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#referenceNumberRefused")
            .classList.remove("is-invalid");
          document
            .querySelector("#referenceNumberRefused")
            .classList.add("is-valid");
        }
        if ($("#palletsRefused").val().length < 1) {
          document.querySelector("#palletsRefused").classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#palletsRefused")
            .classList.remove("is-invalid");
          document.querySelector("#palletsRefused").classList.add("is-valid");
        }
        if ($("#weightRefused").val().length < 1) {
          document.querySelector("#weightRefused").classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#weightRefused")
            .classList.remove("is-invalid");
          document.querySelector("#weightRefused").classList.add("is-valid");
        }
        if ($("#trailerNumberRefused").val().length < 1) {
          document
            .querySelector("#trailerNumberRefused")
            .classList.add("is-invalid");
          valid = false;
        } else {
          document
            .querySelector("#trailerNumberRefused")
            .classList.remove("is-invalid");
          document
            .querySelector("#trailerNumberRefused")
            .classList.add("is-valid");
        }
      }
    }

    if (facility == "accoi" || facility == "acont" || facility == "lineage") {
      if ($("#orderNumber").val().length < 1) {
        document.querySelector("#orderNumber").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#orderNumber").classList.remove("is-invalid");
        document.querySelector("#orderNumber").classList.add("is-valid");
      }
      if ($("#referenceNumber").val().length < 1) {
        document.querySelector("#referenceNumber").classList.add("is-invalid");
        valid = false;
      } else {
        document
          .querySelector("#referenceNumber")
          .classList.remove("is-invalid");
        document.querySelector("#referenceNumber").classList.add("is-valid");
      }
      if ($("#pallets").val().length < 1) {
        document.querySelector("#pallets").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#pallets").classList.remove("is-invalid");
        document.querySelector("#pallets").classList.add("is-valid");
      }
      if ($("#weight").val().length < 1) {
        document.querySelector("#weight").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#weight").classList.remove("is-invalid");
        document.querySelector("#weight").classList.add("is-valid");
      }
      if ($("#trailerNumber").val().length < 1) {
        document.querySelector("#trailerNumber").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#trailerNumber").classList.remove("is-invalid");
        document.querySelector("#trailerNumber").classList.add("is-valid");
      }
    }

    if (facility == "northern") {
      if (!$("#reason").val()) {
        document.querySelector("#reason").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#reason").classList.remove("is-invalid");
      }
      if ($("#reason").val() == "other" && $("#otherReason").val().length < 1) {
        document.querySelector("#otherReason").classList.add("is-invalid");
        valid = false;
      } else {
        document.querySelector("#otherReason").classList.remove("is-invalid");
        document.querySelector("#otherReason").classList.add("is-valid");
      }
    }

    if (facility == "fuel") {
    }

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
      console.log("invalid");
      return false;
    }

    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "text",
    }).done(function (dataa) {
      console.log(dataa);
    });
  });

  $("#facilityName").change(function () {
    var selection = $(this).val();
    console.log("Facility selected: " + selection);

    if (selection == "danone") {
      $("select#arrivalStatus").prop("selectedIndex", 0);
      $("#normalArrival").attr("hidden", true);
      $("#danoneArrival").attr("hidden", false);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
      $("#northernArrival").attr("hidden", true);
      $("#fuelArrival").attr("hidden", true);
    } else if (
      selection == "accoi" ||
      selection == "acont" ||
      selection == "lineage"
    ) {
      $("#danoneArrival").attr("hidden", true);
      $("#normalArrival").attr("hidden", false);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
      $("#northernArrival").attr("hidden", true);
      $("#fuelArrival").attr("hidden", true);
    } else if (selection == "northern") {
      $("#danoneArrival").attr("hidden", true);
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
      $("#northernArrival").attr("hidden", false);
      $("#fuelArrival").attr("hidden", true);
    } else if (selection == "fuel") {
      $("#danoneArrival").attr("hidden", true);
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
      $("#northernArrival").attr("hidden", true);
      $("#fuelArrival").attr("hidden", false);
    }
  });

  $("#reason").change(function () {
    var selection = $(this).val();
    if (selection == "other") {
      $("#other").attr("hidden", false);
    } else {
      $("#other").attr("hidden", true);
    }
  });

  $("#arrivalStatus").change(function () {
    var selection = $(this).val();
    console.log("selected: " + selection);
    if (selection == "bobtail") {
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", false);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
    } else if (selection == "emptyTrailer") {
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", false);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", true);
    } else if (selection == "samples" || selection == "chepPallets") {
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", false);
      $("#refusedLoadArrival").attr("hidden", true);
    } else if (selection == "refusedLoad") {
      $("#normalArrival").attr("hidden", true);
      $("#bobtailArrival").attr("hidden", true);
      $("#emptyArrival").attr("hidden", true);
      $("#palletsOrSamples").attr("hidden", true);
      $("#refusedLoadArrival").attr("hidden", false);
    }
  });

  $(".dropdown-menu").click(function (e) {
    e.stopPropagation();
  });
});

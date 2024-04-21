<?php
add_shortcode( 'comparator-user-form', 'comparator_user_form' );
function comparator_user_form($atts, $content) {

    $result = json_decode($content, true);

    
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    
    ?>

<div class="container mt-5">
  <form id="multiStepForm">
    <!-- Step 1 -->
    <div class="step" id="step1">
      <h2>Step 1: Personal Information</h2>
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" required>
      </div>
      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail Address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" required>
      </div>
      <div class="form-group">
        <label for="address">Physical Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
      </div>
      <button class="btn btn-primary next" type="button">Next</button>
    </div>

    <!-- Step 2 -->
    <div class="step" id="step2" style="display: none;">
      <h2>Step 2: Current Operator</h2>
      <div class="form-group">
        <label for="operator">Select Operator</label>
        <select class="form-control" id="operator" name="operator" required>
          <option value="">Select Operator</option>
          <option value="operator1">Operator 1</option>
          <option value="operator2">Operator 2</option>
          <!-- Add more options as needed -->
        </select>
      </div>
      <button class="btn btn-primary prev" type="button">Previous</button>
      <button class="btn btn-primary next" type="button">Next</button>
    </div>

    <!-- Step 3 -->
    <div class="step" id="step3" style="display: none;">
      <h2>Step 3: Installation Preferences</h2>
      <div class="form-group">
        <label>Preferences for Installation</label>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="asap" name="installationPreferences" value="ASAP">
          <label class="form-check-label" for="asap">ASAP</label>
        </div>
        <!-- Add more options as needed -->
      </div>
      <button class="btn btn-primary prev" type="button">Previous</button>
      <button class="btn btn-primary next" type="button">Next</button>
    </div>

    <!-- Step 4 -->
    <div class="step" id="step4" style="display: none;">
      <h2>Step 4: Confirmation</h2>
      <p>Review your information before submission.</p>
      <!-- Display user input from previous steps for confirmation -->
      <button class="btn btn-primary prev" type="button">Previous</button>
      <button class="btn btn-success" type="submit">Submit</button>
    </div>
  </form>
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function () {
    var currentStep = 1;

    $(".next").click(function () {
      if (validateStep(currentStep)) {
        $("#step" + currentStep).hide();
        currentStep++;
        $("#step" + currentStep).show();
      }
    });

    $(".prev").click(function () {
      $("#step" + currentStep).hide();
      currentStep--;
      $("#step" + currentStep).show();
    });

    function validateStep(step) {
      // Add validation logic for each step here
      return true; // For simplicity, always return true for now
    }

    $("#multiStepForm").submit(function (e) {
      e.preventDefault();
      // Add logic to handle form submission here
      alert("Form submitted successfully!");
    });

    
  });
</script>



    <?php
	return ob_get_clean();
}
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="with=device-width, initial-scale=1.0">
  <title>Feedback</title>
  <link rel="stylesheet" href="home_page.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

  <section class="header">
    <nav>
        <a href="homepage.php"><img src="images/logo.png"></a>
        <div class="nav-links">
            <i class="fa fa-tiems" onclick="hideMenu()"></i>
            <ul>
                <li><a href="homepage.php">HOME</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="register.php">REGISTER</a></li>
                <li><a href="seller.php">SELL</a></li>
                <li><a href="feedback.php">FEEDBACK</a></li>
            </ul>
        </div>
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav>
    <div class="text-box">
        <h1>FEEDBACK FORM</h1>
        <a href=""></a>
    </div>
  </section>
  <footer>
  <script>
    function jumpToSection(sectionId) {
      var targetSection = document.querySelector(sectionId);
  
      if (targetSection) {
        targetSection.scrollIntoView({ behavior: 'smooth' });
      }
    }
  
    window.addEventListener("scroll", function() {
      var backToTopBtn = document.getElementById("backToTopBtn");
      if (window.scrollY > 20) {
        backToTopBtn.style.display = "block";
      } else {
        backToTopBtn.style.display = "none";
      }
    });
  
    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }
  </script>
  
  <script>
    var navLinks = document.getElementById("navLinks");
  
    function showMenu() {
      navLinks.style.right = "0";
    }
  
    function hideMenu() {
      navLinks.style.right = "-200";
    }
  </script>
  
  <script>
    function like(likeCountId) {
        var likeCount = parseInt(localStorage.getItem('likeCount')) || 0;
      likeCount++;
      document.getElementById("likeCount").textContent = likeCount;
      localStorage.setItem('likeCount', likeCount.toString());
    }
  
    function dislike(dislikeCountId) {
        var dislikeCount = parseInt(localStorage.getItem('dislikeCount')) || 0;
      dislikeCount++;
      document.getElementById("dislikeCount").textContent = dislikeCount;
      localStorage.setItem('dislikeCount', likeCount.toString());
    }
  </script>
<footer>
    <div class="text-center">
    <form>
        <br><br>
        <label for="suggestion">Please Write You Comment:</label><br>
        <textarea type="text" id="suggestion" name="suggestion" rows="4" cols="80"></textarea><br>
        <div class="button-submit">
        <button type="submit">Submit</button>
    </form>
    <div id="suggestionList">
        <br><br>
        <h3>Comment List:</h3>
        <table id="suggestions">
          <thead>
            <tr>
              <th>Index</th>
              <th>Comment</th>
            </tr>
            </thead>
          <tbody>
          </tbody>
        </table>
        <br><br>
        <p>Thank You!</p>
      </div>
    </div>
</div>
</footer>
<script>
var suggestions = [];

// Retrieve suggestions from localStorage if available
if (localStorage.getItem('suggestions')) {
  suggestions = JSON.parse(localStorage.getItem('suggestions'));
}

function displaySuggestions() {
  var suggestionTableBody = document.getElementById("suggestions").getElementsByTagName("tbody")[0];
  suggestionTableBody.innerHTML = ""; // Clear existing list

  // Add suggestions to the list
  for (var i = 0; i < suggestions.length; i++) {
  var suggestion = suggestions[i];

  var row = document.createElement("tr");

  var indexCell = document.createElement("td");
  indexCell.textContent = i + 1;

  var suggestionCell = document.createElement("td");
  suggestionCell.textContent = suggestion;

  row.appendChild(indexCell);
  row.appendChild(suggestionCell);

  suggestionTableBody.appendChild(row);
}
}

// Initial display of suggestions/comments
displaySuggestions();

// Add event listener for form submission
var form = document.querySelector('form');
form.addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent default form submission behavior

  var suggestionInput = document.getElementById("suggestion");
  var suggestion = suggestionInput.value.trim();

  if (suggestion !== "") {
    // Add suggestion to the array and display it
    suggestions.push(suggestion);
    displaySuggestions();

    // Save suggestions to localStorage
    localStorage.setItem('suggestions', JSON.stringify(suggestions));

    // Clear the suggestion input field
    suggestionInput.value = "";
  }
});
      </script>
  </footer>
</body>
</html>
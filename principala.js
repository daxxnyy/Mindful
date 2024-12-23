document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("nav ul li a").forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const sectionId = e.target.id;
            loadContent(sectionId);
        });
    });

    // Load initial content
    loadContent('studiu');
});

function loadContent(sectionId) {
    const content = document.getElementById("content");
    content.style.opacity = 0;
    content.style.transform = 'scale(0.95)';

    setTimeout(() => {
        switch(sectionId) {
            case 'studiu':
                content.innerHTML = "<h2>Studiu</h2><p>Aici vor fi afișate cărțile tale.</p>";
                break;
            case 'teste':
                content.innerHTML = "<h2>Teste</h2><p>Aici vor fi afișate testele tale.</p>";
                break;
            case 'forumuri':
                content.innerHTML = "<h2>Forumuri</h2><p>Aici vor fi afișate forumurile tale.</p>";
                break;
            case 'mindful-ai':
                content.innerHTML = "<h2>Mindful AI</h2><p>Aici vor fi afișate informațiile despre Mindful AI.</p>";
                break;
            case 'cont':
                content.innerHTML = "<h2>Cont</h2><p>Aici vor fi afișate informațiile contului tău.</p>";
                break;
            default:
                content.innerHTML = "<h2>Bun venit!</h2><p>Alege o secțiune din meniul de mai jos.</p>";
        }
        content.style.opacity = 1; 
        content.style.transform = 'scale(1)'; 
    }, 500); 
}

document.getElementById("btn-matematica").onclick = function() {
    window.location.href = "pagini-materii/matematica.html";
  };
  document.getElementById("btn-romana").onclick = function() {
    window.location.href = "pagini-materii/romana.html";
  };
  document.getElementById("btn-engleza").onclick = function() {
    window.location.href = "pagini-materii/engleza.html";
  };
  document.getElementById("btn-germana").onclick = function() {
    window.location.href = "pagini-materii/germana.html";
  };
  document.getElementById("btn-franceza").onclick = function() {
    window.location.href = "pagini-materii/franceza.html";
  };
  document.getElementById("btn-fizica").onclick = function() {
    window.location.href = "pagini-materii/fizica.html";
  };
  document.getElementById("btn-chimie").onclick = function() {
    window.location.href = "pagini-materii/chimie.html";
  };
  document.getElementById("btn-istorie").onclick = function() {
    window.location.href = "pagini-materii/istorie.html";
  };
  document.getElementById("btn-geografie").onclick = function() {
    window.location.href = "pagini-materii/geografie.html";
  };
  document.getElementById("btn-civica").onclick = function() {
    window.location.href = "pagini-materii/civica.html";
  };
  document.getElementById("btn-informatica").onclick = function() {
    window.location.href ="pagini-materii/informatica.html";
  };
  document.getElementById("btn-biologie").onclick = function() {
    window.location.href = "pagini-materii/biologie.html";
  };

  

  
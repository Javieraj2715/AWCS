// Muestra u oculta las opciones de examen al hacer clic en Citas
function toggleOptions() {
    const options = document.getElementById('citas-options');
    
    // Alterna la visibilidad de las opciones
    if (options.style.display === 'none' || options.style.display === '') {
        options.style.display = 'block'; // Muestra las opciones
    } else {
        options.style.display = 'none'; // Oculta las opciones
    }
}

// Maneja la selección de examen y muestra el resultado
function handleExamSelection(type) {
    const resultDiv = document.getElementById('result');
    if (type === 'practico') {
        window.location = "practico.html"
      
    } else if (type === 'teorico') {
       
        window.location = "ExamenTeorico.html";
    }
}




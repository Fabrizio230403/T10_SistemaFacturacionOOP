function toggleFacturas(element) {
    const nextElement = element.nextElementSibling; // La lista ul
    const arrow = element.querySelector('.arrow'); // La flecha

    // Alternar la visibilidad de la lista
    if (nextElement.style.display === 'block') {
        nextElement.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)'; // Flecha hacia abajo
    } else {
        nextElement.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)'; // Flecha hacia arriba
    }
}

    // Aparecer secuencialmente los botones
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.btn-animated');
        console.log("Botones encontrados:", buttons.length);
        buttons.forEach((button, index) => {
            setTimeout(() => {
                button.style.opacity = '1';
                button.style.transform = 'translateY(0)';
            }, index * 200); // Retraso entre botones
        });
    });

    // Efecto de ondulación al hacer clic en botones
    document.querySelectorAll('.btn').forEach((btn) => {
        btn.addEventListener('click', function (e) {
            console.log("Botón clicado:", this.textContent);
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = `${e.clientX - btn.getBoundingClientRect().left}px`;
            ripple.style.top = `${e.clientY - btn.getBoundingClientRect().top}px`;
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600); // Elimina el efecto después de 600ms
        });
    });
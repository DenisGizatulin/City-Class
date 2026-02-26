document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    
    // Проверяем сохраненную тему в localStorage
    const currentTheme = localStorage.getItem('theme');

    // Если была сохранена тёмная тема, включаем её
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if(themeToggleBtn) themeToggleBtn.textContent = '☀️ Светлая тема';
    }

    // Обработка клика по кнопке
    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            
            let theme = 'light';
            if (document.body.classList.contains('dark-theme')) {
                theme = 'dark';
                themeToggleBtn.textContent = '☀️ Светлая тема';
            } else {
                themeToggleBtn.textContent = '🌙 Тёмная тема';
            }
            
            // Сохраняем выбор
            localStorage.setItem('theme', theme);
        });
    }
});
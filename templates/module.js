tailwind.config = {
    theme: {
        extend: {
            colors: {
                custom: {
                    black: '#000000',
                    orange: '#FF9900',
                    darkGray: '#222222',
                    mediumGray: '#333333',
                    lightGray: '#CCCCCC',
                },
            },
        },
    },
}
feather.replace();


function createModuleCard(module, index) {
    const card = document.createElement('div');
    card.classList.add('card');
    card.style.backgroundColor = index % 2 === 0 ? '#FF9900' : '#000000';
    
    card.innerHTML = `
        <div class="card-title">${module.title}</div>
        <div class="card-content">${module.content}</div>
        <div class="card-stats">
            <span>${module.stats.questions.toLocaleString()} questions</span>
            <span>${module.stats.askedToday} asked today, ${module.stats.askedThisWeek} this week</span>
        </div>
    `;
    
    return card;
}

function initializeModules() {
    const grid = document.getElementById('moduleGrid');
    moduleData.forEach((module, index) => {
        grid.appendChild(createModuleCard(module, index));
    });
    feather.replace();
}
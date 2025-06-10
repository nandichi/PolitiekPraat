<?php require_once 'views/templates/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary to-secondary 
                           rounded-full shadow-2xl mb-6 transform hover:scale-110 transition-all duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold bg-gradient-to-r from-primary via-secondary to-primary 
                      bg-clip-text text-transparent mb-6">
                Programma Vergelijker
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Vergelijk de standpunten van Nederlandse politieke partijen over belangrijke thema's. 
                Ontdek waar partijen overeenkomen en waar ze van elkaar verschillen.
            </p>
        </div>

        <!-- Interactive Comparison Tool -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <!-- Control Panel -->
            <div class="bg-gradient-to-r from-primary/5 via-secondary/5 to-primary/5 p-8 border-b border-gray-100">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Party Selection -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Selecteer Partijen
                        </h3>
                        <div class="grid grid-cols-2 gap-3" id="party-selector">
                            <?php foreach($data['parties'] as $key => $party): ?>
                                <label class="flex items-center space-x-3 p-3 rounded-xl bg-white border-2 border-gray-200 
                                             hover:border-primary/30 cursor-pointer transition-all duration-300 
                                             hover:shadow-md group">
                                    <input type="checkbox" 
                                           class="party-checkbox w-5 h-5 text-primary border-2 border-gray-300 rounded-lg 
                                                 focus:ring-primary focus:ring-2" 
                                           data-party="<?php echo $key; ?>" 
                                           data-color="<?php echo $party['color']; ?>">
                                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                                        <img src="<?php echo $party['logo']; ?>" 
                                             alt="<?php echo $party['name']; ?>" 
                                             class="w-8 h-8 rounded-lg object-cover border border-gray-200 
                                                   group-hover:scale-110 transition-transform duration-300"
                                             onerror="this.style.display='none'">
                                        <span class="text-sm font-medium text-gray-700 truncate group-hover:text-primary 
                                                   transition-colors duration-300">
                                            <?php echo $key; ?>
                                        </span>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Theme Selection -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Selecteer Thema's
                        </h3>
                        <div class="space-y-3" id="theme-selector">
                            <?php foreach($data['themes'] as $key => $theme): ?>
                                <label class="flex items-center space-x-3 p-3 rounded-xl bg-white border-2 border-gray-200 
                                             hover:border-secondary/30 cursor-pointer transition-all duration-300 
                                             hover:shadow-md group">
                                    <input type="checkbox" 
                                           class="theme-checkbox w-5 h-5 text-secondary border-2 border-gray-300 rounded-lg 
                                                 focus:ring-secondary focus:ring-2" 
                                           data-theme="<?php echo $key; ?>">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <span class="text-2xl group-hover:scale-110 transition-transform duration-300">
                                            <?php echo $theme['icon']; ?>
                                        </span>
                                        <div>
                                            <span class="font-medium text-gray-700 group-hover:text-secondary 
                                                       transition-colors duration-300">
                                                <?php echo $theme['title']; ?>
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <?php echo $theme['description']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Compare Button -->
                <div class="mt-8 text-center">
                    <button id="compare-btn" 
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-secondary 
                                  text-white font-semibold rounded-2xl shadow-xl hover:shadow-2xl 
                                  transform hover:scale-105 transition-all duration-300 
                                  disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Vergelijk Standpunten
                    </button>
                </div>
            </div>

            <!-- Comparison Results -->
            <div id="comparison-results" class="hidden p-8">
                <div id="results-content"></div>
            </div>
        </div>

        <!-- Quick Compare Cards -->
        <div class="mt-16" data-aos="fade-up" data-aos-delay="400">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                Snelle Vergelijkingen
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Grootste Partijen -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl 
                           transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 
                               rounded-2xl mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Grootste Partijen</h3>
                    <p class="text-gray-600 text-center mb-4">Vergelijk PVV, GL-PvdA en VVD</p>
                    <button class="quick-compare w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium 
                                  py-3 px-4 rounded-xl transition-all duration-300" 
                            data-parties="PVV,GL-PvdA,VVD" 
                            data-themes="Immigratie,Klimaat,Zorg">
                        Vergelijk Nu
                    </button>
                </div>

                <!-- Links vs Rechts -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl 
                           transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-500 to-green-500 
                               rounded-2xl mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Links vs Rechts</h3>
                    <p class="text-gray-600 text-center mb-4">SP, GL-PvdA tegenover VVD, PVV</p>
                    <button class="quick-compare w-full bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium 
                                  py-3 px-4 rounded-xl transition-all duration-300" 
                            data-parties="SP,GL-PvdA,VVD,PVV" 
                            data-themes="Economie,Zorg,Onderwijs">
                        Vergelijk Nu
                    </button>
                </div>

                <!-- Klimaatfocus -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl 
                           transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 
                               rounded-2xl mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Klimaat & Energie</h3>
                    <p class="text-gray-600 text-center mb-4">Vergelijk alle partijen op klimaat</p>
                    <button class="quick-compare w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium 
                                  py-3 px-4 rounded-xl transition-all duration-300" 
                            data-parties="GL-PvdA,D66,PvdD,VVD,PVV" 
                            data-themes="Klimaat,Energie,Duurzaamheid">
                        Vergelijk Nu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const parties = <?php echo json_encode($data['parties']); ?>;
    const themes = <?php echo json_encode($data['themes']); ?>;
    
    const compareBtn = document.getElementById('compare-btn');
    const resultsDiv = document.getElementById('comparison-results');
    const resultsContent = document.getElementById('results-content');
    
    // Quick compare functionality
    document.querySelectorAll('.quick-compare').forEach(button => {
        button.addEventListener('click', function() {
            const selectedParties = this.dataset.parties.split(',');
            const selectedThemes = this.dataset.themes.split(',');
            
            // Check the checkboxes
            document.querySelectorAll('.party-checkbox').forEach(cb => {
                cb.checked = selectedParties.includes(cb.dataset.party);
            });
            
            document.querySelectorAll('.theme-checkbox').forEach(cb => {
                cb.checked = selectedThemes.includes(cb.dataset.theme);
            });
            
            // Trigger comparison
            updateCompareButton();
            performComparison();
        });
    });
    
    // Enable/disable compare button
    function updateCompareButton() {
        const selectedParties = Array.from(document.querySelectorAll('.party-checkbox:checked'));
        const selectedThemes = Array.from(document.querySelectorAll('.theme-checkbox:checked'));
        
        compareBtn.disabled = selectedParties.length < 2 || selectedThemes.length < 1;
    }
    
    // Listen for checkbox changes
    document.querySelectorAll('.party-checkbox, .theme-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateCompareButton);
    });
    
    // Perform comparison
    function performComparison() {
        const selectedParties = Array.from(document.querySelectorAll('.party-checkbox:checked'))
            .map(cb => cb.dataset.party);
        const selectedThemes = Array.from(document.querySelectorAll('.theme-checkbox:checked'))
            .map(cb => cb.dataset.theme);
        
        if (selectedParties.length < 2 || selectedThemes.length < 1) {
            return;
        }
        
        let html = '<div class="space-y-8">';
        
        selectedThemes.forEach(themeKey => {
            const theme = themes[themeKey];
            html += `
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center mb-6">
                        <span class="text-3xl mr-4">${theme.icon}</span>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">${theme.title}</h3>
                            <p class="text-gray-600">${theme.description}</p>
                        </div>
                    </div>
                    
                    <div class="grid gap-4">
            `;
            
            selectedParties.forEach(partyKey => {
                const party = parties[partyKey];
                const standpoint = party.standpoints[themeKey] || 'Geen specifiek standpunt bekend';
                
                html += `
                    <div class="bg-white rounded-xl p-4 border-l-4 shadow-sm hover:shadow-md transition-all duration-300" 
                         style="border-left-color: ${party.color}">
                        <div class="flex items-start space-x-4">
                            <img src="${party.logo}" alt="${party.name}" 
                                 class="w-12 h-12 rounded-lg object-cover border border-gray-200"
                                 onerror="this.style.display='none'">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-semibold text-gray-800">${party.name}</h4>
                                    <span class="text-sm text-gray-500">(${party.current_seats} zetels)</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">${standpoint}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += `
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        
        resultsContent.innerHTML = html;
        resultsDiv.classList.remove('hidden');
        
        // Smooth scroll to results
        resultsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Compare button click
    compareBtn.addEventListener('click', performComparison);
    
    // Initial state
    updateCompareButton();
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 
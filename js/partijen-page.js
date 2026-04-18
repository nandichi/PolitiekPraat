(function(){
document.addEventListener('DOMContentLoaded', function() {
    // Party data from PHP
    const partyData = window.__PP_PARTIES__;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            // Replace with a local fallback image if loading fails
            this.src = '/public/images/profiles/placeholder-profile.svg';
            this.onerror = null; // Prevent infinite loop
        };
    });
    
    // Party buttons - navigate to detail page
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            window.location.href = `${window.PP_CONFIG.urlRoot}/partijen/${partyKey}`;
        });
    });
    

    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const partyModal = document.getElementById('party-modal');
        const leaderModal = document.getElementById('leader-modal');
        
        if (event.target === partyModal) {
            partyModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (event.target === leaderModal) {
            leaderModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
    
    // Enhanced Sorting functionality
    document.getElementById('sortOption').addEventListener('change', function() {
        const sortMethod = this.value;
        const partyCards = Array.from(document.querySelectorAll('.party-card'));
        const partyGrid = document.getElementById('parties-grid');
        
        // Add loading animation
        partyGrid.style.opacity = '0.5';
        partyGrid.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
            // Sort the cards
            partyCards.sort((a, b) => {
                const aPartyKey = a.querySelector('.party-btn').getAttribute('data-party');
                const bPartyKey = b.querySelector('.party-btn').getAttribute('data-party');
                
                if (sortMethod === 'name') {
                    return aPartyKey.localeCompare(bPartyKey);
                } else if (sortMethod === 'seats') {
                    return partyData[bPartyKey].current_seats - partyData[aPartyKey].current_seats;
                } else if (sortMethod === 'polling') {
                    return partyData[bPartyKey].polling.seats - partyData[aPartyKey].polling.seats;
                }
                
                return 0;
            });
            
            // Remove existing cards
            partyCards.forEach(card => card.remove());
            
            // Add the sorted cards back with staggered animation
            partyCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                partyGrid.appendChild(card);
                
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            // Restore grid
            partyGrid.style.transition = 'all 0.3s ease';
            partyGrid.style.opacity = '1';
            partyGrid.style.transform = 'scale(1)';
        }, 150);
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const partyCounter = document.getElementById('party-counter');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const partyCards = document.querySelectorAll('.party-card');
            let visibleCount = 0;
            
            partyCards.forEach(card => {
                const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
                const party = partyData[partyKey];
                const searchText = `${partyKey} ${party.name} ${party.leader}`.toLowerCase();
                
                if (searchText.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.3s ease';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update counter
            partyCounter.textContent = visibleCount;
        });
    }
    
    // View toggle functionality (Grid/List)
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const partiesGrid = document.getElementById('parties-grid');
    
    if (gridViewBtn && listViewBtn) {
        listViewBtn.addEventListener('click', function() {
            // Switch to list view
            partiesGrid.className = 'space-y-3 mb-12';
            
            // Update button states
            gridViewBtn.classList.remove('bg-primary', 'text-white');
            gridViewBtn.classList.add('text-gray-600');
            listViewBtn.classList.add('bg-primary', 'text-white');
            listViewBtn.classList.remove('text-gray-600');
            
            // Transform cards for list view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToListView(card);
            });
        });
        
        gridViewBtn.addEventListener('click', function() {
            // Switch to grid view
            partiesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-12';
            
            // Update button states
            listViewBtn.classList.remove('bg-primary', 'text-white');
            listViewBtn.classList.add('text-gray-600');
            gridViewBtn.classList.add('bg-primary', 'text-white');
            gridViewBtn.classList.remove('text-gray-600');
            
            // Transform cards back to grid view
            document.querySelectorAll('.party-card').forEach(card => {
                transformToGridView(card);
            });
        });
    }
    
    // Enhanced list view transformation
    function transformToListView(card) {
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        const color = getPartyColor(partyKey);
        const changeValue = party.polling.change;
        
                 // Create horizontal list layout with better alignment
         card.innerHTML = `
             <div class="flex items-center p-6 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                 <!-- Left Section: Party Identity (Fixed width) -->
                 <div class="flex items-center space-x-4 w-80">
                     <!-- Logo & Party Info -->
                     <div class="flex items-center space-x-4">
                         <div class="relative flex-shrink-0">
                             <div class="w-14 h-14 rounded-xl bg-white shadow-md border-2 border-gray-100 flex items-center justify-center overflow-hidden">
                                 <img src="${party.logo}" alt="${party.name} logo" class="w-10 h-10 object-contain">
                             </div>
                             <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: ${color}"></div>
                         </div>
                         <div class="min-w-0">
                             <h3 class="text-lg font-bold text-gray-900">${partyKey}</h3>
                         </div>
                     </div>
                     
                     <!-- Leader Info -->
                     <div class="flex items-center space-x-3 pl-6 border-l border-gray-200">
                         <div class="w-10 h-10 rounded-full overflow-hidden border-2 shadow-sm flex-shrink-0" style="border-color: ${color}">
                             <img src="${party.leader_photo}" alt="${party.leader}" class="w-full h-full object-cover">
                         </div>
                         <div class="min-w-0">
                             <p class="text-sm font-semibold text-gray-800 truncate">${party.leader}</p>
                             <p class="text-xs text-gray-500">Partijleider</p>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Center Section: Key Stats (Fixed widths for alignment) -->
                 <div class="flex items-center space-x-12 flex-1 justify-center">
                     <!-- Current Seats -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold text-gray-900">${party.current_seats}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Huidige zetels</div>
                     </div>
                     
                     <!-- Polling -->
                     <div class="text-center w-24">
                         <div class="text-2xl font-bold" style="color: ${color}">${parseInt(party.polling?.seats) || 0}</div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Peilingen</div>
                     </div>
                     
                     <!-- Trend -->
                     <div class="text-center w-28">
                         <div class="flex items-center justify-center mb-1">
                             ${changeValue > 0 ? 
                                 `<div class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-200 text-sm font-bold">📈 +${changeValue}</div>` :
                                 changeValue < 0 ? 
                                 `<div class="bg-red-100 text-red-700 px-2 py-1 rounded-lg border border-red-200 text-sm font-bold">📉 ${changeValue}</div>` :
                                 `<div class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg border border-blue-200 text-sm font-bold">➡️ 0</div>`
                             }
                         </div>
                         <div class="text-xs text-gray-500 uppercase tracking-wide">Trend</div>
                     </div>
                 </div>
                 
                 <!-- Right Section: Actions -->
                 <div class="flex items-center justify-end">
                     <button class="party-btn bg-gradient-to-r text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300" 
                             style="background: linear-gradient(135deg, ${color}, ${adjustColorBrightness(color, -20)});"
                             data-party="${partyKey}">
                         <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                         </svg>
                         Bekijk partij
                     </button>
                 </div>
             </div>
         `;
        
        // Re-attach event listeners for the new buttons
        attachButtonListeners(card);
    }
    
    // Restore grid view
    function transformToGridView(card) {
        // Get the original party key to rebuild the card
        const partyKey = card.querySelector('.party-btn').getAttribute('data-party');
        const party = partyData[partyKey];
        
        // Restore original card HTML (this would need the original template)
        location.reload(); // Simple solution - reload to restore original cards
    }
    
    // Helper function to attach event listeners to new buttons
    function attachButtonListeners(card) {
        const partyBtn = card.querySelector('.party-btn');
        
        if (partyBtn) {
            partyBtn.addEventListener('click', function() {
                // Navigate to party detail page instead of showing modal
                const partyKey = this.getAttribute('data-party');
                window.location.href = `${window.PP_CONFIG.urlRoot}/partijen/${partyKey}`;
            });
        }
    }
    
    // Enhanced tab switching for chamber views
    const currentTab = document.getElementById('current-tab');
    const pollingTab = document.getElementById('polling-tab');
    const currentView = document.getElementById('current-view');
    const pollingView = document.getElementById('polling-view');
    
    if (currentTab && pollingTab && currentView && pollingView) {
        currentTab.addEventListener('click', function() {
            // Update current tab styling
            currentTab.classList.add('bg-white/30');
            currentTab.classList.remove('bg-white/20');
            
            // Update polling tab styling
            pollingTab.classList.remove('bg-white/30');
            pollingTab.classList.add('bg-white/20', 'text-white/70');
            pollingTab.classList.remove('text-white');
            
            // Switch views with animation
            pollingView.classList.add('hidden');
            currentView.classList.remove('hidden');
            
            // Trigger chamber visualization for current seats
            setTimeout(() => {
                createChamberVisualization();
            }, 100);
        });
        
        pollingTab.addEventListener('click', function() {
            // Update polling tab styling
            pollingTab.classList.add('bg-white/30', 'text-white');
            pollingTab.classList.remove('bg-white/20', 'text-white/70');
            
            // Update current tab styling
            currentTab.classList.remove('bg-white/30');
            currentTab.classList.add('bg-white/20', 'text-white/70');
            currentTab.classList.remove('text-white');
            
            // Switch views with animation
            currentView.classList.add('hidden');
            pollingView.classList.remove('hidden');
            
            // Trigger chamber visualization for polling seats
            setTimeout(() => {
                createChamberVisualization();
            }, 100);
        });
    }
    
    // Create premium chamber visualization with enhanced animations
    function createChamberVisualization() {
        const currentSeatsContainer = document.getElementById('current-seats-chamber');
        const pollingSeatsContainer = document.getElementById('polling-seats-chamber');
        
        if (!currentSeatsContainer || !pollingSeatsContainer) return;
        
        // Clear containers with fade effect
        fadeOutAndClear(currentSeatsContainer);
        fadeOutAndClear(pollingSeatsContainer);
        
        // Define realistic Dutch Parliament layout (semicircle arrangement)
        const chamberLayout = [
            8,   // Back row
            12,  // 
            16,  // 
            20,  // 
            24,  // 
            28,  // 
            32,  // Front center
            10   // Speaker area
        ];
        
        // Create seats for both views with staggered animation
        setTimeout(() => {
            createPremiumChamberSeats(currentSeatsContainer, chamberLayout, 'current');
            createPremiumChamberSeats(pollingSeatsContainer, chamberLayout, 'polling');
        }, 300);
    }
    
    function fadeOutAndClear(container) {
        container.style.transition = 'opacity 0.3s ease';
        container.style.opacity = '0';
        setTimeout(() => {
            container.innerHTML = '<div class="text-slate-500 font-medium animate-pulse">Zetels worden geladen...</div>';
            container.style.opacity = '1';
        }, 300);
    }
    
    function createPremiumChamberSeats(container, layout, type) {
        let seatCount = 0;
        const totalSeats = 150;
        let partySeats = [];
        
        // Collect all parties with their seats (current or polling)
        for (const [partyKey, party] of Object.entries(partyData)) {
            const seatNum = type === 'current' ? 
                parseInt(party.current_seats) || 0 : 
                parseInt(party.polling?.seats) || 0;
            if (seatNum > 0) {
                partySeats.push({
                    party: partyKey,
                    count: seatNum,
                    color: getPartyColor(partyKey),
                    partyData: party
                });
            }
        }
        
        // Sort by seat count for better visual distribution
        partySeats.sort((a, b) => b.count - a.count);
        
        // Create mixed array for realistic distribution
        let allSeats = [];
        partySeats.forEach(partySeat => {
            for (let i = 0; i < partySeat.count; i++) {
                allSeats.push({
                    party: partySeat.party,
                    color: partySeat.color,
                    partyData: partySeat.partyData
                });
            }
        });
        
        // Smart shuffle for realistic clustering
        allSeats = smartShuffle(allSeats);
        
        // Add empty seats if needed
        while (allSeats.length < totalSeats) {
            allSeats.push({ party: 'empty', color: '#E5E7EB', isEmpty: true });
        }
        
        // Clear loading message
        container.innerHTML = '';
        
        // Create rows of seats with enhanced styling
        layout.forEach((seatsInRow, rowIndex) => {
            const row = document.createElement('div');
            row.className = 'flex justify-center items-center mb-2 gap-1.5 md:gap-2';
            row.style.marginBottom = `${4 + rowIndex * 2}px`;
            
            for (let i = 0; i < seatsInRow && seatCount < totalSeats; i++) {
                const seatData = allSeats[seatCount];
                const seat = createPremiumSeat(seatData, type, seatCount);
                
                // Add staggered animation
                seat.style.animationDelay = `${seatCount * 8}ms`;
                
                row.appendChild(seat);
                seatCount++;
            }
            
            container.appendChild(row);
        });
    }
    
    function smartShuffle(array) {
        // Keep some clustering while still shuffling
        const result = [...array];
        for (let i = result.length - 1; i > 0; i--) {
            // Bias towards nearby positions for more realistic clustering
            const maxDistance = Math.min(5, i);
            const j = Math.max(0, i - Math.floor(Math.random() * maxDistance));
            [result[i], result[j]] = [result[j], result[i]];
        }
        return result;
    }
    
    function createPremiumSeat(seatData, type, index) {
        const seat = document.createElement('div');
        seat.className = 'premium-seat w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 rounded-full border-2 border-white shadow-lg cursor-pointer relative transform hover:scale-125 transition-all duration-300 opacity-0';
        
        // Add entrance animation
        seat.style.animation = 'seatFadeIn 0.6s ease-out forwards';
        
        if (seatData.isEmpty) {
            seat.style.backgroundColor = seatData.color;
            seat.style.opacity = '0.3';
            seat.style.cursor = 'default';
        } else {
            seat.style.backgroundColor = seatData.color;
            seat.style.boxShadow = `0 4px 12px ${seatData.color}40, 0 2px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.3)`;
            
            // Enhanced tooltip
            const tooltip = createPremiumTooltip(seatData, type);
            seat.appendChild(tooltip);
            
            // Premium interactions
            seat.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.5) translateY(-3px)';
                this.style.zIndex = '100';
                this.style.boxShadow = `0 8px 25px ${seatData.color}60, 0 4px 12px rgba(0,0,0,0.15)`;
                
                const tooltip = this.querySelector('.premium-tooltip');
                if (tooltip) {
                    tooltip.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    tooltip.classList.add('opacity-100', 'scale-100');
                }
            });
            
            seat.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0px)';
                this.style.zIndex = '10';
                this.style.boxShadow = `0 4px 12px ${seatData.color}40, 0 2px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.3)`;
                
                const tooltip = this.querySelector('.premium-tooltip');
                if (tooltip) {
                    tooltip.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    tooltip.classList.remove('opacity-100', 'scale-100');
                }
            });
            
            // Click to view party details
            seat.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `${window.PP_CONFIG.urlRoot}/partijen/${seatData.party}`;
            });
        }
        
        seat.dataset.party = seatData.party;
        return seat;
    }
    
    function createPremiumTooltip(seatData, type) {
        const tooltip = document.createElement('div');
        tooltip.className = 'premium-tooltip absolute bottom-full left-1/2 transform -translate-x-1/2 mb-4 bg-black/95 backdrop-blur-sm text-white px-4 py-3 rounded-xl text-sm whitespace-nowrap opacity-0 scale-95 transition-all duration-200 pointer-events-none z-50 shadow-2xl border border-white/20';
        
        const seats = type === 'current' ? seatData.partyData.current_seats : seatData.partyData.polling.seats;
        const percentage = ((seats / 150) * 100).toFixed(1);
        
        tooltip.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden bg-white/20 p-1 flex-shrink-0">
                    <img src="${seatData.partyData.logo}" alt="${seatData.party}" class="w-full h-full object-contain" onerror="this.style.display='none'">
                </div>
                <div>
                    <div class="font-bold text-white">${seatData.party}</div>
                    <div class="text-xs text-gray-300 truncate max-w-32">${seatData.partyData.name}</div>
                    <div class="text-xs mt-1">
                        <span class="text-blue-300 font-medium">${seats} zetels</span>
                        <span class="text-gray-400"> • ${percentage}%</span>
                    </div>
                </div>
            </div>
            <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-black/95"></div>
        `;
        
        return tooltip;
    }
    
    // Initialize chamber visualization
    setTimeout(() => {
        createChamberVisualization();
    }, 500);
    
    // REMOVED: highlightPartySeats and resetHighlights functions
    // These functions are no longer needed since we removed the hover highlighting functionality
        

    
    // Assign colors to parties
    function getPartyColor(partyKey) {
        const partyColors = {
            'PVV': '#0078D7', 
            'VVD': '#FF9900',
            'NSC': '#4D7F78',
            'BBB': '#006633',
            'GL-PvdA': '#008800',
            'D66': '#00B13C',
            'SP': '#EE0000',
            'PvdD': '#007E3A',
            'CDA': '#1E8449',
            'JA21': '#0066CC',
            'SGP': '#FF6600', 
            'FvD': '#811E1E',
            'DENK': '#00b7b2',
            'Volt': '#502379',
            'CU': '#00AEEF'
        };
        
        return partyColors[partyKey] || '#A0A0A0';
    }
    
    // Helper function to adjust color brightness for JavaScript
    function adjustColorBrightness(hex, steps) {
        // Remove the # if present
        hex = hex.replace('#', '');
        
        // Parse r, g, b values
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        
        // Adjust brightness
        const newR = Math.max(0, Math.min(255, r + steps));
        const newG = Math.max(0, Math.min(255, g + steps));
        const newB = Math.max(0, Math.min(255, b + steps));
        
        // Convert back to hex
        const toHex = (n) => {
            const hex = n.toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };
        
        return '#' + toHex(newR) + toHex(newG) + toHex(newB);
    }
});

// Completely Rebuilt Modern Coalition Maker - Tap/Click Only
document.addEventListener('DOMContentLoaded', function() {
    const NewCoalitionMaker = {
        parties: window.__PP_PARTIES__,
        currentView: 'current',
        selectedParties: new Set(),
        
        // Political spectrum positions (0 = far left, 100 = far right)
        partyPositions: {
            'PVV': 85, 'VVD': 70, 'NSC': 45, 'BBB': 60, 'GL-PvdA': 15,
            'D66': 30, 'SP': 10, 'PvdD': 20, 'CDA': 50, 'JA21': 80,
            'SGP': 75, 'FvD': 90, 'DENK': 25, 'Volt': 35, 'CU': 40
        },
        
        partyColors: {
            'PVV': '#0078D7', 'VVD': '#FF9900', 'NSC': '#4D7F78', 'BBB': '#006633',
            'GL-PvdA': '#008800', 'D66': '#00B13C', 'SP': '#EE0000', 'PvdD': '#007E3A',
            'CDA': '#1E8449', 'JA21': '#0066CC', 'SGP': '#FF6600', 'FvD': '#811E1E',
            'DENK': '#00b7b2', 'Volt': '#502379', 'CU': '#00AEEF'
        },
        
        init() {
            this.setupEventListeners();
            this.renderPartyGrid();
            this.renderHemicycle();
            this.updateAllMetrics();
            this.generateSuggestions();
        },
        
        setupEventListeners() {
            // Tab switching
            document.getElementById('coalition-current-tab')?.addEventListener('click', () => this.switchView('current'));
            document.getElementById('coalition-polling-tab')?.addEventListener('click', () => this.switchView('polling'));
            
            // Action buttons
            document.getElementById('clear-all-btn')?.addEventListener('click', () => this.clearAll());
            document.getElementById('random-coalition-btn')?.addEventListener('click', () => this.randomCoalition());
        },
        
        switchView(view) {
            this.currentView = view;
            
            // Update tabs
            const currentTab = document.getElementById('coalition-current-tab');
            const pollingTab = document.getElementById('coalition-polling-tab');
            
            if (view === 'current') {
                currentTab?.classList.add('active');
                currentTab?.classList.remove('text-white/60');
                pollingTab?.classList.remove('active');
                pollingTab?.classList.add('text-white/60');
            } else {
                pollingTab?.classList.add('active');
                pollingTab?.classList.remove('text-white/60');
                currentTab?.classList.remove('active');
                currentTab?.classList.add('text-white/60');
            }
            
            this.renderPartyGrid();
            this.updateAllMetrics();
        },
        
        renderPartyGrid() {
            const container = document.getElementById('party-grid');
            if (!container) return;
            
            container.innerHTML = '';
            
            // Sort parties by seats
            const sorted = Object.entries(this.parties).sort((a, b) => {
                const seatsA = this.getSeats(a[0]);
                const seatsB = this.getSeats(b[0]);
                return seatsB - seatsA;
            });
            
            sorted.forEach(([key, party]) => {
                const seats = this.getSeats(key);
                if (seats > 0) {
                    const card = this.createPartyCard(key, party, seats);
                    container.appendChild(card);
                }
            });
        },
        
        createPartyCard(key, party, seats) {
            const card = document.createElement('div');
            const isSelected = this.selectedParties.has(key);
            const color = this.partyColors[key] || '#A0A0A0';
            
            card.className = `party-card cursor-pointer rounded-xl p-3 md:p-4 transition-all duration-300 border-2 ${
                isSelected 
                    ? 'bg-gradient-to-br from-primary/10 to-secondary/10 border-primary shadow-lg scale-105' 
                    : 'bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 hover:shadow-md'
            }`;
            
            card.innerHTML = `
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden bg-white border border-gray-200 flex-shrink-0 flex items-center justify-center shadow-sm">
                            <img src="${party.logo}" alt="${key}" class="w-8 h-8 md:w-10 md:h-10 object-contain p-1">
                            </div>
                            <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-sm md:text-base truncate">${key}</h4>
                                <p class="text-xs text-gray-600 truncate hidden sm:block">${party.name}</p>
                            </div>
                        </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                            <span class="text-xs font-semibold text-gray-700">${seats} zetels</span>
                            </div>
                        ${isSelected ? `
                            <div class="flex items-center gap-1 text-primary">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                            ` : ''}
                        </div>
                    </div>
            `;
            
            card.addEventListener('click', () => this.toggleParty(key));
            return card;
        },
        
        toggleParty(key) {
            if (this.selectedParties.has(key)) {
                this.selectedParties.delete(key);
            } else {
                this.selectedParties.add(key);
            }
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        clearAll() {
            this.selectedParties.clear();
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        randomCoalition() {
            this.selectedParties.clear();
            const parties = Object.keys(this.parties).filter(k => this.getSeats(k) > 0);
            let totalSeats = 0;
            
            // Shuffle and select until we have a majority
            const shuffled = parties.sort(() => Math.random() - 0.5);
            for (const party of shuffled) {
                if (totalSeats >= 76) break;
                this.selectedParties.add(party);
                totalSeats += this.getSeats(party);
                if (this.selectedParties.size >= 6) break; // Max 6 parties
            }
            
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        },
        
        getSeats(key) {
            const party = this.parties[key];
            return this.currentView === 'current' 
                ? parseInt(party.current_seats) || 0 
                : parseInt(party.polling?.seats) || 0;
        },
        
        getTotalSeats() {
            let total = 0;
            this.selectedParties.forEach(key => {
                total += this.getSeats(key);
            });
            return total;
        },
        
        updateAllMetrics() {
            const totalSeats = this.getTotalSeats();
            const percentage = Math.round((totalSeats / 150) * 100);
            const partyCount = this.selectedParties.size;
            const hasMajority = totalSeats >= 76;
            
            // Update all displays
            this.animateNumber('header-seats', totalSeats);
            this.animateNumber('header-parties', partyCount);
            this.animateNumber('dashboard-seats', totalSeats);
            this.animateNumber('progress-seats', totalSeats);
            this.animateNumber('dashboard-party-count', partyCount);
            this.animateNumber('hemicycle-selected', totalSeats);
            
            document.getElementById('dashboard-percentage').textContent = percentage + '%';
            
            // Update progress bar
            const progressBar = document.getElementById('main-progress-bar');
            if (progressBar) {
                progressBar.style.width = Math.min(100, (totalSeats / 150) * 100) + '%';
            }
            
            // Update status
            const statusIcon = document.getElementById('dashboard-status-icon');
            const statusText = document.getElementById('dashboard-status-text');
            
            if (totalSeats === 0) {
                statusText.textContent = 'Geen coalitie';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center';
            } else if (hasMajority) {
                statusText.textContent = 'Meerderheid!';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center';
            } else {
                statusText.textContent = (76 - totalSeats) + ' tekort';
                statusIcon.className = 'w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center';
            }
        },
        
        animateNumber(elementId, targetValue) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const startValue = parseInt(element.textContent) || 0;
            const duration = 500;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(startValue + (targetValue - startValue) * easeOut);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        renderHemicycle() {
            const svg = document.getElementById('hemicycle-svg');
            if (!svg) return;
            
            svg.innerHTML = '';
            
            // Configuration
            const centerX = 200;
            const centerY = 180;
            const rows = 5;
            const seatsPerRow = [20, 25, 30, 35, 40];
            const startRadius = 60;
            const radiusIncrement = 20;
            
            let seatIndex = 0;
            const selectedKeys = Array.from(this.selectedParties);
            let currentPartyIndex = 0;
            let seatsForCurrentParty = selectedKeys.length > 0 ? this.getSeats(selectedKeys[0]) : 0;
            
            // Draw seats in hemicycle
            for (let row = 0; row < rows; row++) {
                const radius = startRadius + (row * radiusIncrement);
                const numSeats = seatsPerRow[row];
                const startAngle = Math.PI;
                const endAngle = 0;
                const angleStep = (startAngle - endAngle) / (numSeats - 1);
                
                for (let i = 0; i < numSeats; i++) {
                    const angle = startAngle - (i * angleStep);
                    const x = centerX + radius * Math.cos(angle);
                    const y = centerY - radius * Math.sin(angle);
                    
                    let color = '#E5E7EB'; // gray-200 for empty seats
                    
                    // Assign color if seat is part of selected coalition
                    if (seatIndex < this.getTotalSeats()) {
                        if (seatsForCurrentParty > 0) {
                            color = this.partyColors[selectedKeys[currentPartyIndex]] || '#A0A0A0';
                            seatsForCurrentParty--;
                            
                            if (seatsForCurrentParty === 0 && currentPartyIndex < selectedKeys.length - 1) {
                                currentPartyIndex++;
                                seatsForCurrentParty = this.getSeats(selectedKeys[currentPartyIndex]);
                            }
                        }
                    }
                    
                    // Create seat circle
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', x);
                    circle.setAttribute('cy', y);
                    circle.setAttribute('r', '3.5');
                    circle.setAttribute('fill', color);
                    circle.setAttribute('stroke', '#fff');
                    circle.setAttribute('stroke-width', '0.5');
                    
                    svg.appendChild(circle);
                    seatIndex++;
                }
            }
        },
        
        updateSpectrum() {
            const container = document.getElementById('spectrum-indicators');
            const info = document.getElementById('spectrum-info');
            
            if (!container || !info) return;
            
            container.innerHTML = '';
            
            if (this.selectedParties.size === 0) {
                info.innerHTML = '<div class="text-sm text-gray-600">Selecteer partijen om het politieke spectrum te zien</div>';
                return;
            }
            
            // Calculate weighted average
            let totalPosition = 0;
            let totalSeats = 0;
            
            this.selectedParties.forEach(key => {
                const position = this.partyPositions[key] || 50;
                const seats = this.getSeats(key);
                totalPosition += position * seats;
                totalSeats += seats;
            });
            
            const avgPosition = totalPosition / totalSeats;
            
            // Place indicators for each party
            this.selectedParties.forEach(key => {
                const position = this.partyPositions[key] || 50;
                const color = this.partyColors[key] || '#A0A0A0';
                
                const indicator = document.createElement('div');
                indicator.className = 'absolute w-3 h-8 -top-1 transform -translate-x-1/2 transition-all duration-500';
                indicator.style.left = position + '%';
                indicator.innerHTML = `
                    <div class="w-full h-full rounded-full shadow-lg border-2 border-white" style="background-color: ${color}"></div>
                `;
                
                container.appendChild(indicator);
            });
            
            // Describe political orientation
            let description = '';
            if (avgPosition < 25) description = 'Sterk links-georiënteerde coalitie';
            else if (avgPosition < 40) description = 'Links-georiënteerde coalitie';
            else if (avgPosition < 48) description = 'Centrum-links coalitie';
            else if (avgPosition < 53) description = 'Centrum coalitie';
            else if (avgPosition < 65) description = 'Centrum-rechts coalitie';
            else if (avgPosition < 80) description = 'Rechts-georiënteerde coalitie';
            else description = 'Sterk rechts-georiënteerde coalitie';
            
            info.innerHTML = `<div class="text-sm font-semibold text-gray-900">${description}</div>`;
        },
        
        generateSuggestions() {
            const container = document.getElementById('suggestions-container');
            if (!container) return;
            
            const suggestions = [
                { name: 'Grote Coalitie', parties: ['PVV', 'VVD', 'GL-PvdA', 'NSC'], description: 'Vier grootste partijen' },
                { name: 'Paars-Plus', parties: ['VVD', 'D66', 'GL-PvdA', 'Volt'], description: 'Progressief-liberaal' },
                { name: 'Nationaal Compromis', parties: ['VVD', 'NSC', 'GL-PvdA', 'CDA', 'D66'], description: 'Breed centrum' },
                { name: 'Rechts Blok', parties: ['PVV', 'VVD', 'NSC', 'BBB', 'JA21'], description: 'Conservatief-liberaal' }
            ];
            
            container.innerHTML = suggestions.map(sug => {
                // Calculate seats for polling view
                const totalSeats = sug.parties.reduce((sum, key) => {
                    const party = this.parties[key];
                    return sum + (parseInt(party?.polling?.seats) || 0);
                }, 0);
                
                if (totalSeats < 76) return ''; // Only show coalitions with majority
                
                return `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200 hover:border-primary hover:shadow-md transition-all cursor-pointer"
                         onclick="NewCoalitionMaker.applySuggestion(${JSON.stringify(sug.parties).replace(/"/g, '&quot;')})">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-bold text-gray-900 text-sm">${sug.name}</h4>
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-primary/10 text-primary">${totalSeats} zetels</span>
                            </div>
                        <p class="text-xs text-gray-600 mb-3">${sug.description}</p>
                        <div class="flex flex-wrap gap-2">
                            ${sug.parties.map(key => {
                                const party = this.parties[key];
                                if (!party) return '';
                                const color = this.partyColors[key] || '#A0A0A0';
                                return `
                                    <div class="flex items-center gap-1 bg-white rounded-lg px-2 py-1 border border-gray-200">
                                        <div class="w-2 h-2 rounded-full" style="background-color: ${color}"></div>
                                        <span class="text-xs font-semibold">${key}</span>
                        </div>
                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }).filter(html => html !== '').join('');
        },
        
        applySuggestion(partyKeys) {
            this.selectedParties.clear();
            partyKeys.forEach(key => {
                if (this.parties[key] && this.getSeats(key) > 0) {
                    this.selectedParties.add(key);
                }
            });
            this.renderPartyGrid();
            this.updateAllMetrics();
            this.renderHemicycle();
            this.updateSpectrum();
        }
    };
    
    // Initialize
    NewCoalitionMaker.init();
    window.NewCoalitionMaker = NewCoalitionMaker;
    
    // Additional party functionality (existing code)
    const partyData = window.__PP_PARTIES__;
    
    // Handle image errors
    document.querySelectorAll('img').forEach(img => {
        img.onerror = function() {
            this.src = '/public/images/profiles/placeholder-profile.svg';
            this.onerror = null;
        };
    });
    
    // Party buttons for modals
    document.querySelectorAll('.party-btn').forEach(button => {
        button.addEventListener('click', function() {
            const partyKey = this.getAttribute('data-party');
            const party = partyData[partyKey];
            
            // Fill modal with party data
            document.getElementById('party-modal-title').textContent = party.name;
            document.getElementById('party-modal-logo').src = party.logo;
            document.getElementById('party-modal-logo').alt = `${party.name} logo`;
            document.getElementById('party-modal-abbr').textContent = partyKey;
            document.getElementById('party-modal-name').textContent = party.name;
            document.getElementById('party-modal-leader').textContent = party.leader;
            document.getElementById('party-modal-leader-photo').src = party.leader_photo;
            document.getElementById('party-modal-leader-photo').alt = party.leader;
            document.getElementById('party-modal-description').textContent = party.description;
            document.getElementById('party-modal-seats').textContent = parseInt(party.current_seats) || 0;
            document.getElementById('party-modal-polling').textContent = parseInt(party.polling?.seats) || 0;
            
            // Fill perspectives
            document.getElementById('party-modal-left-perspective').textContent = party.perspectives.left;
            document.getElementById('party-modal-right-perspective').textContent = party.perspectives.right;
            
            // Display polling trend
            const trendElement = document.getElementById('party-modal-polling-trend');
            const change = party.polling.change;
            const changeClass = change > 0 ? 'text-green-600' : (change < 0 ? 'text-red-600' : 'text-yellow-600');
            const changeIcon = change > 0 ? '↑' : (change < 0 ? '↓' : '→');
            const changeText = change > 0 ? `+${change}` : change;
            
            trendElement.className = `text-sm font-medium ${changeClass}`;
            trendElement.textContent = change !== 0 ? `Trend: ${changeIcon} ${changeText}` : 'Stabiel in peilingen';
            
            // Fill standpoints
            const standpointsContainer = document.getElementById('party-modal-standpoints');
            standpointsContainer.innerHTML = '';
            
            for (const [topic, standpoint] of Object.entries(party.standpoints)) {
                const standpointEl = document.createElement('div');
                standpointEl.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200';
                standpointEl.innerHTML = `
                    <h4 class="font-semibold text-gray-800 mb-2">${topic}</h4>
                    <p class="text-gray-600 text-sm">${standpoint}</p>
                `;
                standpointsContainer.appendChild(standpointEl);
            }
            
            // Show modal
            document.getElementById('party-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    
    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('party-modal').classList.add('hidden');
            document.getElementById('leader-modal').classList.add('hidden');
            document.body.style.overflow = '';
        });
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const partyModal = document.getElementById('party-modal');
        const leaderModal = document.getElementById('leader-modal');
        
        if (event.target === partyModal) {
            partyModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (event.target === leaderModal) {
            leaderModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // AI Analysis functionality
    const aiBtn = document.getElementById('ai-analysis-btn');
    const aiModal = document.getElementById('ai-modal');
    const aiModalContent = document.getElementById('ai-modal-content');
    const closeAiModal = document.getElementById('close-ai-modal');
    const aiLoading = document.getElementById('ai-loading');
    const aiContent = document.getElementById('ai-content');
    const aiError = document.getElementById('ai-error');
    const retryBtn = document.getElementById('retry-ai-analysis');

    function openAiModal() {
        aiModal.classList.remove('hidden');
        setTimeout(() => {
            aiModal.classList.remove('opacity-0');
            aiModalContent.classList.remove('scale-95');
            aiModalContent.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
        
        // Start AI analysis
        performAiAnalysis();
    }

    function closeAiModalFunc() {
        aiModal.classList.add('opacity-0');
        aiModalContent.classList.remove('scale-100');
        aiModalContent.classList.add('scale-95');
        setTimeout(() => {
            aiModal.classList.add('hidden');
            // Reset states
            aiLoading.classList.remove('hidden');
            aiContent.classList.add('hidden');
            aiError.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function performAiAnalysis() {
        // Show loading state
        aiLoading.classList.remove('hidden');
        aiContent.classList.add('hidden');
        aiError.classList.add('hidden');

        // Gather polling data for AI analysis
        const pollingData = {
            parties: window.__PP_PARTIES__,
            date: new Date().toISOString().split('T')[0]
        };

        fetch('ajax/ai-polling-analysis.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(pollingData)
        })
        .then(response => response.json())
        .then(data => {
            aiLoading.classList.add('hidden');
            
            if (data.success) {
                aiContent.innerHTML = formatAiResponse(data.content);
                aiContent.classList.remove('hidden');
            } else {
                showAiError();
            }
        })
        .catch(error => {
            console.error('AI Analysis Error:', error);
            showAiError();
        });
    }
    
    function showAiError() {
        aiLoading.classList.add('hidden');
        aiError.classList.remove('hidden');
    }
    
    function formatAiResponse(content) {
        // Format AI response with proper styling
        const sections = content.split('\n\n');
        return sections.map(section => {
            if (section.startsWith('#')) {
                const title = section.replace(/^#+\s*/, '');
                return `<h3 class="text-xl font-bold text-gray-900 mb-3 mt-6 first:mt-0">${title}</h3>`;
            } else if (section.startsWith('-')) {
                const items = section.split('\n').filter(line => line.trim());
                const listItems = items.map(item => `<li>${item.replace(/^-\s*/, '')}</li>`).join('');
                return `<ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">${listItems}</ul>`;
            } else {
                return `<p class="text-gray-700 leading-relaxed mb-4">${section}</p>`;
            }
        }).join('');
    }
    
    // Event listeners for AI modal
    if (aiBtn) {
        aiBtn.addEventListener('click', openAiModal);
    }
    
    if (closeAiModal) {
        closeAiModal.addEventListener('click', closeAiModalFunc);
    }
    
    if (retryBtn) {
        retryBtn.addEventListener('click', performAiAnalysis);
    }
    
    // Close AI modal when clicking outside
    if (aiModal) {
        aiModal.addEventListener('click', function(e) {
            if (e.target === aiModal) {
                closeAiModalFunc();
            }
        });
    }
    
    // Close AI modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !aiModal.classList.contains('hidden')) {
            closeAiModalFunc();
        }
    });
});

})();

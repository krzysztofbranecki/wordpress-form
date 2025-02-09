document.addEventListener('DOMContentLoaded', function() {
    const entryItems = document.querySelectorAll('.entry-item');
    const entryDetails = document.querySelector('.entry-details');
    const entryContent = document.querySelector('.entry-content');
    const entriesList = document.querySelector('.entries-items');
    const loadMoreBtn = document.querySelector('.load-more-button');
    const entriesCount = document.querySelector('.entries-count');
    
    // Handle entry click for details
    function attachEntryClickHandlers() {
        document.querySelectorAll('.entry-item:not([data-handler-attached])').forEach(item => {
            item.addEventListener('click', handleEntryClick);
            item.setAttribute('data-handler-attached', 'true');
        });
    }
    
    async function handleEntryClick() {
        const entryId = this.dataset.entryId;
        
        try {
            const response = await fetch(`${frontItFormSettings.restUrl}/entries/${entryId}`, {
                headers: {
                    'X-WP-Nonce': frontItFormSettings.nonce
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            // Clear previous selection
            document.querySelectorAll('.entry-item.active').forEach(el => {
                el.classList.remove('active');
            });
            
            // Mark current item as active
            this.classList.add('active');
            
            // Display entry details
            let detailsHtml = '<div class="entry-fields">';
            for (const [key, value] of Object.entries(data.fields)) {
                detailsHtml += `
                    <div class="entry-field">
                        <strong>${key}:</strong>
                        <span>${value}</span>
                    </div>
                `;
            }
            detailsHtml += '</div>';
            
            entryContent.innerHTML = detailsHtml;
            entryDetails.style.display = 'block';
            
        } catch (error) {
            console.error('Error fetching entry details:', error);
            entryContent.innerHTML = `<p class="error">${frontItFormSettings.i18n.errorLoadingDetails}</p>`;
        }
    }
    
    // Handle load more
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', async function() {
            // Prevent double clicks
            if (this.disabled) return;
            
            // Set loading state
            const originalText = this.textContent;
            this.textContent = frontItFormSettings.i18n.loading;
            this.disabled = true;
            
            const page = parseInt(this.dataset.page);
            const limit = parseInt(this.dataset.limit);
            const offset = page * limit;
            
            try {
                const response = await fetch(`${frontItFormSettings.restUrl}/entries?limit=${limit}&offset=${offset}`, {
                    headers: {
                        'X-WP-Nonce': frontItFormSettings.nonce
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                if (data.entries && data.entries.length > 0) {
                    // Append new entries
                    data.entries.forEach(entry => {
                        const li = document.createElement('li');
                        li.className = 'entry-item';
                        li.dataset.entryId = entry.id;
                        li.innerHTML = `
                            <div class="entry-preview">
                                <span class="entry-date">${entry.created_at}</span>
                                <span class="entry-name">${entry.first_name} ${entry.last_name}</span>
                                <span class="entry-email">${entry.email}</span>
                            </div>
                        `;
                        entriesList.appendChild(li);
                    });
                    
                    // Update handlers for new entries
                    attachEntryClickHandlers();
                    
                    // Update page number
                    this.dataset.page = page + 1;
                    
                    // Get current count of entries
                    const currentCount = document.querySelectorAll('.entry-item').length;
                    
                    // Update entries count - ensure we don't show more than total
                    if (entriesCount) {
                        const displayCount = Math.min(currentCount, data.total);
                        entriesCount.textContent = frontItFormSettings.i18n.showingEntries
                            .replace('%1$d', displayCount)
                            .replace('%2$d', data.total);
                    }
                    
                    // Hide load more button if no more entries
                    if (currentCount >= data.total) {
                        this.style.display = 'none';
                    } else {
                        // Reset button state
                        this.textContent = frontItFormSettings.i18n.loadMore;
                        this.disabled = false;
                    }
                }
            } catch (error) {
                console.error('Error loading more entries:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                errorDiv.textContent = frontItFormSettings.i18n.errorLoadingMore;
                this.parentNode.insertBefore(errorDiv, this);
                
                // Reset button state
                this.textContent = originalText;
                this.disabled = false;
            }
        });
    }
    
    // Initial attachment of click handlers
    attachEntryClickHandlers();
});
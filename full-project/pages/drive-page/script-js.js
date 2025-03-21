document.addEventListener('DOMContentLoaded', function() {
    // Sample file data
    const fileData = [
        { 
            id: 1, 
            name: 'Prezentacja projektu', 
            type: 'document', 
            modified: '21 mar 2025', 
            size: '2.4 MB',
            starred: true
        },
        { 
            id: 2, 
            name: 'Raport finansowy Q1_2025', 
            type: 'spreadsheet', 
            modified: '18 mar 2025', 
            size: '1.1 MB',
            starred: false
        },
        { 
            id: 3, 
            name: 'Grafika promocyjna', 
            type: 'image', 
            modified: '15 mar 2025', 
            size: '3.8 MB',
            starred: false
        },
        { 
            id: 4, 
            name: 'Wideo instruktażowe', 
            type: 'video', 
            modified: '10 mar 2025', 
            size: '45.6 MB',
            starred: true
        },
        { 
            id: 5, 
            name: 'Dokumentacja API', 
            type: 'pdf', 
            modified: '5 mar 2025', 
            size: '4.2 MB',
            starred: false
        },
        { 
            id: 6, 
            name: 'Plan marketingowy 2025', 
            type: 'document', 
            modified: '1 mar 2025', 
            size: '3.1 MB',
            starred: false
        },
        { 
            id: 7, 
            name: 'Dane klientów', 
            type: 'spreadsheet', 
            modified: '25 lut 2025', 
            size: '0.9 MB',
            starred: false
        },
        { 
            id: 8, 
            name: 'Logo firmy', 
            type: 'image', 
            modified: '20 lut 2025', 
            size: '0.4 MB',
            starred: true
        }
    ];

    // Initialize file display
    renderFiles();

    // Event listeners
    setupViewToggle();
    setupContextMenu();
    setupCreateButton();
    setupSortOptions();
    setupSearch();
    setupDropArea();

    // Render files in grid or list view
    function renderFiles() {
        // Sort files by modified date (most recent first)
        const sortedFiles = [...fileData].sort((a, b) => {
            return new Date(parseDate(b.modified)) - new Date(parseDate(a.modified));
        });
        
        const recentFiles = sortedFiles.slice(0, 4);
        renderFileSection(recentFiles, 'recentFiles');
        renderFileSection(sortedFiles, 'allFiles');
    }

    function parseDate(dateStr) {
        const monthMap = {
            'sty': 0, 'lut': 1, 'mar': 2, 'kwi': 3, 'maj': 4, 'cze': 5,
            'lip': 6, 'sie': 7, 'wrz': 8, 'paź': 9, 'lis': 10, 'gru': 11
        };
        
        const parts = dateStr.split(' ');
        const day = parseInt(parts[0], 10);
        const month = monthMap[parts[1]];
        const year = parseInt(parts[2], 10);
        
        return new Date(year, month, day);
    }

    function renderFileSection(files, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        const isGridView = document.getElementById('gridView').classList.contains('active');
        
        if (isGridView) {
            container.className = 'files-grid';
            files.forEach(file => {
                container.appendChild(createFileCard(file));
            });
        } else {
            container.className = 'files-list';
            
            // Add list header for list view
            if (files.length > 0) {
                const header = document.createElement('div');
                header.className = 'list-header';
                header.innerHTML = `
                    <div>Nazwa</div>
                    <div>Data modyfikacji</div>
                    <div>Rozmiar pliku</div>
                    <div></div>
                `;
                container.appendChild(header);
                
                files.forEach(file => {
                    container.appendChild(createFileRow(file));
                });
            }
        }
    }

    function createFileCard(file) {
        const card = document.createElement('div');
        card.className = 'file-card';
        card.dataset.fileId = file.id;
        
        let iconSrc = '';
        switch(file.type) {
            case 'document':
                iconSrc = '/api/placeholder/80/80';
                break;
            case 'spreadsheet':
                iconSrc = '/api/placeholder/80/80';
                break;
            case 'image':
                iconSrc = '/api/placeholder/80/80';
                break;
            case 'video':
                iconSrc = '/api/placeholder/80/80';
                break;
            case 'pdf':
                iconSrc = '/api/placeholder/80/80';
                break;
            default:
                iconSrc = '/api/placeholder/80/80';
        }
        
        card.innerHTML = `
            <div class="file-thumbnail">
                <img src="${iconSrc}" alt="${file.type} icon">
            </div>
            <div class="file-info">
                <div class="file-name">${file.name}</div>
                <div class="file-meta">${file.modified} · ${file.size}</div>
            </div>
        `;
        
        // Add context menu event
        card.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showContextMenu(e, file);
        });
        
        return card;
    }

    function createFileRow(file) {
        const row = document.createElement('div');
        row.className = 'file-row';
        row.dataset.fileId = file.id;
        
        let iconSrc = '';
        switch(file.type) {
            case 'document':
                iconSrc = '/api/placeholder/20/20';
                break;
            case 'spreadsheet':
                iconSrc = '/api/placeholder/20/20';
                break;
            case 'image':
                iconSrc = '/api/placeholder/20/20';
                break;
            case 'video':
                iconSrc = '/api/placeholder/20/20';
                break;
            case 'pdf':
                iconSrc = '/api/placeholder/20/20';
                break;
            default:
                iconSrc = '/api/placeholder/20/20';
        }
        
        row.innerHTML = `
            <div class="file-icon">
                <img src="${iconSrc}" alt="${file.type} icon">
                <span>${file.name}</span>
            </div>
            <div>${file.modified}</div>
            <div>${file.size}</div>
            <div class="list-actions">
                <img src="/api/placeholder/16/16" alt="More" class="more-actions">
            </div>
        `;
        
        // Add context menu event
        row.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showContextMenu(e, file);
        });
        
        row.querySelector('.more-actions').addEventListener('click', function(e) {
            e.stopPropagation();
            const rect = this.getBoundingClientRect();
            showContextMenu({ clientX: rect.right, clientY: rect.bottom }, file);
        });
        
        return row;
    }

    function setupViewToggle() {
        const gridViewBtn = document.getElementById('gridView');
        const listViewBtn = document.getElementById('listView');
        
        gridViewBtn.addEventListener('click', function() {
            if (!gridViewBtn.classList.contains('active')) {
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                renderFiles();
            }
        });
        
        listViewBtn.addEventListener('click', function() {
            if (!listViewBtn.classList.contains('active')) {
                listViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
                renderFiles();
            }
        });
    }

    function setupContextMenu() {
        const contextMenu = document.getElementById('contextMenu');
        
        // Close context menu when clicking elsewhere
        document.addEventListener('click', function() {
            contextMenu.style.display = 'none';
        });
        
        // Prevent menu closing when clicking inside it
        contextMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Add functionality to menu items
        const menuItems = contextMenu.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Implementation for each menu action would go here
                contextMenu.style.display = 'none';
            });
        });
    }

    function showContextMenu(e, file) {
        const contextMenu = document.getElementById('contextMenu');
        contextMenu.style.display = 'block';
        
        // Position the menu
        const x = e.clientX;
        const y = e.clientY;
        
        // Adjust if menu would go off screen
        const menuWidth = contextMenu.offsetWidth;
        const menuHeight = contextMenu.offsetHeight;
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;
        
        if (x + menuWidth > windowWidth) {
            contextMenu.style.left = (windowWidth - menuWidth - 5) + 'px';
        } else {
            contextMenu.style.left = x + 'px';
        }
        
        if (y + menuHeight > windowHeight) {
            contextMenu.style.top = (windowHeight - menuHeight - 5) + 'px';
        } else {
            contextMenu.style.top = y + 'px';
        }
    }

    function setupCreateButton() {
        const createBtn = document.querySelector('.create-btn');
        const uploadModal = document.getElementById('uploadModal');
        const closeModalBtn = document.querySelector('.close-modal');
        const cancelBtn = document.querySelector('.cancel-btn');
        
        createBtn.addEventListener('click', function() {
            uploadModal.style.display = 'flex';
        });
        
        closeModalBtn.addEventListener('click', function() {
            uploadModal.style.display = 'none';
        });
        
        cancelBtn.addEventListener('click', function() {
            uploadModal.style.display = 'none';
        });
        
        // Close modal when clicking outside content
        uploadModal.addEventListener('click', function(e) {
            if (e.target === uploadModal) {
                uploadModal.style.display = 'none';
            }
        });
    }

    function setupSortOptions() {
        const sortName = document.getElementById('sortName');
        
        sortName.addEventListener('click', function() {
            // Toggle sort order
            if (this.dataset.sort === 'asc') {
                this.dataset.sort = 'desc';
                this.textContent = 'Nazwa ↓';
            } else {
                this
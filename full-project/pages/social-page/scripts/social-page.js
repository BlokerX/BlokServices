// Plik JavaScript obsługujący podstronę społecznościową

/*
 * Inicjalizacja podstrony
 */
function initializeSubpage() {
    // Inicjalizacja dodawania posta
    initializePostAddition();

    // Inicjalizacja filtrów
    initializeFilters();

    // Inicjalizacja przycisku załaduj więcej
    initializeLoadMore();

    // Inicjalizacja sugestii znajomych
    initializeFriendSuggestions();

    loadPosts(); // Ładowanie postów na początku
}

function initializePostAddition() {
    // Obsługa przycisku dodawania posta
    const addPostButton = document.getElementById('add-post-btn');
    if (addPostButton) {
        addPostButton.addEventListener('click', function () {
            const title = ""/*document.getElementById('post-title').value.trim()*/;
            const content = document.getElementById('post-content').value.trim();
            const accessLevelf = document.getElementById('new-post-access-level');
            console.log(accessLevelf);
            const accessLevel = accessLevelf.value;
            const isCommentable = document.getElementById('post-commentable').checked;

            if (content === "") {
                showNotification('Treść posta nie może być pusta', 'error');
                return;
            };

            // Wyślij post do serwera
            submitPost(title, content, accessLevel, isCommentable);
            console.log(`Dodano post: ${title}, ${content}, ${accessLevel}, ${isCommentable}`);

            // // Dodaj post do interfejsu
            // addPostToUI(title, content, accessLevel, isCommentable);

            filterPosts("recent"); // Odświeżenie postów po dodaniu nowego
            // Ustawienie filtra na "najnowsze"
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => btn.classList.remove('active'));
            const recentFilter = document.querySelector('.filter-btn[data-filter="recent"]');
            if (recentFilter) {
                recentFilter.classList.add('active');
            }

            // Wyczyść pola
            //document.getElementById('post-title').value = '';
            document.getElementById('post-content').value = '';
            //document.querySelector('input[name="access-level"][value="public"]').checked = true;
            //document.getElementById('is-commentable').checked = true;
        });
    };
}

/*
 * Inicjalizacja interakcji z postami (lajki, komentarze)
 */
function initializePostInteractions() {
    // Obsługa przycisków lubię to
    const likeButtons = document.querySelectorAll('.like-btn');
    likeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.closest('.post-card').dataset.postId;
            const isLiked = this.dataset.liked === 'true';
            const likeCountElement = this.closest('.post-card').querySelector('.likes-count span');
            let likeCount = parseInt(likeCountElement.textContent);

            // Zmień stan przycisku
            if (isLiked) {
                this.dataset.liked = 'false';
                this.innerHTML = '<i class="far fa-thumbs-up"></i> Lubię to';
                this.classList.remove('active');
                likeCount--;
            } else {
                this.dataset.liked = 'true';
                this.innerHTML = '<i class="fas fa-thumbs-up"></i> Lubię to';
                this.classList.add('active');
                likeCount++;
            }

            // Aktualizuj licznik lajków
            likeCountElement.textContent = likeCount;

            // Informacja (w prawdziwej implementacji byłoby wysłanie do API)
            console.log(`Post ${postId} lajk zmieniony na: ${!isLiked}`);

            console.log(`postId: ${postId}, isLiked: ${!isLiked}`);
            // Symulacja wysłania danych do serwera
            updatePostLikeStatus(postId, !isLiked);
        });
    });

    // Obsługa przycisków komentowania
    const commentButtons = document.querySelectorAll('.comment-btn');
    commentButtons.forEach(button => {
        button.addEventListener('click', function () {
            const commentsSection = this.closest('.post-card').querySelector('.comments-section');

            // Przełącz widoczność sekcji komentarzy
            if (commentsSection.style.display === 'none') {
                commentsSection.style.display = 'block';
                // Znajdź pole wprowadzania i ustaw na nim fokus
                commentsSection.querySelector('input').focus();
            } else {
                commentsSection.style.display = 'none';
            }
        });
    });

    // Obsługa przycisków odpowiedzi na komentarze
    const commentReplyButtons = document.querySelectorAll('.comment-reply-btn');
    commentReplyButtons.forEach(button => {
        button.addEventListener('click', function () {
            const commentContent = this.closest('.comment-content');
            const author = commentContent.querySelector('h5').textContent;
            const inputField = this.closest('.comments-section').querySelector('.add-comment input');

            inputField.value = `@${author} `;
            inputField.focus();
        });
    });

    // Obsługa przycisków edytowania komentarzy
    const commentEditButtons = document.querySelectorAll('.comment-edit-btn');
    commentEditButtons.forEach(button => {
        button.addEventListener('click', function () {
            const comment = this.closest('.comment');
            const commentId = comment.dataset.commentId; // Zakładamy, że ID komentarza jest w atrybucie data-comment-id

            // Wywołaj funkcję edytującą komentarz
            editCommentProcedure(commentId);
        });
    });

    // Obsługa przycisków usuwania komentarzy
    const commentDeleteButtons = document.querySelectorAll('.comment-delete-btn');
    commentDeleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const comment = this.closest('.comment');
            const commentId = comment.dataset.commentId; // Zakładamy, że ID komentarza jest w atrybucie data-comment-id

            // Usunięcie komentarza z interfejsu
            comment.remove();

            // Wyślij żądanie usunięcia komentarza do serwera
            deleteComment(commentId);
        });
    });

    // Obsługa przycisków dodawania komentarzy
    const submitCommentButtons = document.querySelectorAll('.submit-comment-btn');
    submitCommentButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postCard = this.closest('.post-card');
            const postId = postCard.dataset.postId;
            const commentInput = this.closest('.add-comment').querySelector('input');
            const commentText = commentInput.value.trim();

            if (commentText) {
                // Dodaj komentarz do interfejsu
                addCommentToUI(postCard, commentText);

                // Wyślij komentarz do serwera
                submitComment(postId, commentText);

                // Wyczyść pole
                commentInput.value = '';

                // Przeładuj posty
                filterPosts("recent"); // Odświeżenie postów po dodaniu nowego
            }
        });
    });

    // Obsługa pola komentarza i wysyłania przez Enter
    const commentInputs = document.querySelectorAll('.add-comment input');
    commentInputs.forEach(input => {
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const postCard = this.closest('.post-card');
                const postId = postCard.dataset.postId;
                const commentText = this.value.trim();

                if (commentText) {
                    // Dodaj komentarz do interfejsu
                    addCommentToUI(postCard, commentText);

                    // Wyślij komentarz do serwera
                    submitComment(postId, commentText);

                    // Wyczyść pole
                    this.value = '';
                }
            }
        });
    });

    // Obsługa akcji komentarzy (lajk, odpowiedź)
    const commentLikeButtons = document.querySelectorAll('.comment-like-btn');
    commentLikeButtons.forEach(button => {
        button.addEventListener('click', function () {

            const isLiked = this.dataset.liked === 'true';
            const likeCountElement = this.querySelector('.likes-count');
            console.log(likeCountElement + " " + likeCountElement.textContent);
            let likeCount = parseInt(likeCountElement.textContent) || 0;

            // Zmień stan przycisku
            if (isLiked) {
                this.dataset.liked = 'false';
                likeCount--;

                this.innerHTML = '<i class="far fa-thumbs-up"></i> <span class="likes-count">'
                +likeCount+
                ' </span>Lubię to';

                this.classList.remove('active');
            } else {
                this.dataset.liked = 'true';
                likeCount++;

                this.innerHTML = '<i class="fas fa-thumbs-up"></i> <span class="likes-count">'
                +likeCount+
                ' </span>Lubię to';

                this.classList.add('active');
            }

            if (likeCount == 0) this.innerHTML = '<i class="fas fa-thumbs-up"></i> <span class="likes-count"></span>Lubię to';

            console.log('isLiked: ' + this.classList.contains('active'));
            updateCommentLikeStatus(this.closest('.comment').dataset.commentId, this.classList.contains('active'));
        });
    });

    // Obsługa opcji postów
    const postOptions = document.querySelectorAll('.post-options');
    postOptions.forEach(option => {
        option.addEventListener('click', function () {
            const postId = this.closest('.post-card').dataset.postId;
            showPostMenu(this, postId);
        });
    });
}

/**
 * Dodaje komentarz do interfejsu użytkownika
 * @param {HTMLElement} postCard - Element karty postu
 * @param {string} commentText - Treść komentarza
 */
function addCommentToUI(postCard, commentText) {
    const commentsContainer = postCard.querySelector('.comments-container');
    const commentCount = postCard.querySelector('.comments-count');
    const count = parseInt(commentCount.textContent) || 0;

    // Stwórz nowy element komentarza
    const newComment = document.createElement('div');
    newComment.className = 'comment';
    newComment.innerHTML = `
        <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
        <div class="comment-content">
            <h5>Ty</h5>
            <p>${commentText}</p>
            <div class="comment-actions">
                <span class="comment-time">Teraz</span>
                <button class="comment-like-btn">Lubię to</button>
                <button class="comment-reply-btn">Odpowiedz</button>
            </div>
        </div>
    `;

    // Dodaj komentarz do kontenera
    commentsContainer.appendChild(newComment);

    // Zaktualizuj liczbę komentarzy
    commentCount.textContent = `${count + 1} komentarzy`;

    // Dodaj obsługę zdarzeń do nowych przycisków
    const likeBtn = newComment.querySelector('.comment-like-btn');
    likeBtn.addEventListener('click', function () {
        this.classList.toggle('active');
        this.style.color = this.classList.contains('active') ? 'var(--primary-color)' : '';
    });

    const replyBtn = newComment.querySelector('.comment-reply-btn');
    replyBtn.addEventListener('click', function () {
        const commentContent = this.closest('.comment-content');
        const author = commentContent.querySelector('h5').textContent;
        const inputField = this.closest('.comments-section').querySelector('.add-comment input');

        inputField.value = `@${author} `;
        inputField.focus();
    });
}

// add post to UI
function addPostToUI(title, content, accessLevel, isCommentable) {
    const postsContainer = document.querySelector('.posts-container');
    const newPost = document.createElement('div');
    newPost.className = 'post-card';
    newPost.dataset.postId = Math.floor(Math.random() * 1000) + 1; // Losowe ID postu

    newPost.innerHTML = `
        <div class="post-header">
            <div class="post-author">
                <img src="../../assets/img/avatars/default-avatar.png" alt="Avatar użytkownika">
                <div class="author-info">
                    <h4>Ty</h4>
                    <span class="post-time">Teraz</span>
                </div>
            </div>
            <div class="post-options">
                <i class="fas fa-ellipsis-h"></i>
            </div>
        </div>
        <div class="post-content">
            <p>${content}</p>
        </div>
        <div class="post-interactions">
            <div class="interaction-stats">
                <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>0</span></span>
                <span class="comments-count">0 komentarzy</span>
            </div>
            <div class="interaction-buttons">
                <button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>
                <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
                <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
            </div>
        </div>
        <div class="comments-section" style="display: none;">
            <div class="comments-container"></div>
            <div class="add-comment">
                <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
                <input type="text" placeholder="Napisz komentarz...">
                <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    `;
}

// submit post to server

function submitPost(title, content, accessLevel, isCommentable) {
    console.log(`Wysyłanie posta: ${title}, ${content}, ${accessLevel}, ${isCommentable}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
        }
    }

    xmlhttp.open("POST", "../../api/social-page/add_post.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`title: ${title}, content: ${content}, accessLevel: ${accessLevel}, isCommentable: ${isCommentable}`);
    xmlhttp.send("title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content) + "&accessLevel=" + encodeURIComponent(accessLevel) + "&isCommentable=" + encodeURIComponent(isCommentable));
}

/**
 * Aktualizuje status lajka na serwerze
 * @param {number} postId - ID postu
 * @param {boolean} isLiked - Status lajka
 */
function updatePostLikeStatus(postId, isLiked) {
    console.log(`Wysyłanie aktualizacji lajka dla postu ${postId}: ${isLiked}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
        }
    }

    xmlhttp.open("POST", "../../api/social-page/like_post.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`postId: ${postId}, isLiked: ${isLiked}`);
    xmlhttp.send("postId=" + encodeURIComponent(postId) + "&isLiked=" + encodeURIComponent(isLiked));
}

/**
 * Aktualizuje status lajka na serwerze
 * @param {number} commentId - ID komentarza
 * @param {boolean} isLiked - Status lajka
 */
function updateCommentLikeStatus(commentId, isLiked) {
    console.log(`Wysyłanie aktualizacji lajka dla komentarza ${commentId}: ${isLiked}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
        }
    }

    xmlhttp.open("POST", "../../api/social-page/like_comment.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`commentId: ${commentId}, isLiked: ${isLiked}`);
    xmlhttp.send("commentId=" + encodeURIComponent(commentId) + "&isLiked=" + encodeURIComponent(isLiked));
}

/**
 * Wysyła komentarz do serwera
 * @param {number} postId - ID postu
 * @param {string} commentText - Treść komentarza
 */
function submitComment(postId, commentText) {
    console.log(`Wysyłanie komentarza dla postu ${postId}: ${commentText}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
        }
    }

    xmlhttp.open("POST", "../../api/social-page/add_comment.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`postId: ${postId}, commentText: ${commentText}`);
    xmlhttp.send("postId=" + encodeURIComponent(postId) + "&commentText=" + encodeURIComponent(commentText));
}

/**
 * Usuwa komentarz z serwera
 * @param {number} commentId - ID komentarza
 */
function deleteComment(commentId) {
    console.log(`Usuwanie komentarza o ID: ${commentId}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.response);
            let result = JSON.parse(this.response);
            if (result.status === "success") {
                showNotification('Komentarz został usunięty', 'success');
            }
            else {
                showNotification('Nie udało się usunąć komentarza', 'error');
            }
        }
    }

    xmlhttp.open("POST", "../../api/social-page/delete_comment.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`commentId: ${commentId}`);
    xmlhttp.send("commentId=" + encodeURIComponent(commentId));
}

/**
 * Edytuje komentarz na serwerze
 * @param {number} commentId - ID komentarza
 * @param {string} newContent - Nowa treść komentarza
 * */
function updateComment(commentId, newContent) {
    console.log(`Edycja komentarza o ID: ${commentId}, nowa treść: ${newContent}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
        }
    }

    xmlhttp.open("POST", "../../api/social-page/edit_comment.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`commentId: ${commentId}, newContent: ${newContent}`);
    xmlhttp.send("commentId=" + encodeURIComponent(commentId) + "&newContent=" + encodeURIComponent(newContent));
}

/**
 *  Procedura edytowania komentarza
 * @param {number} commentId - ID komentarza
 */
function editCommentProcedure(commentId) {
    const comment = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
    const commentContent = comment.querySelector('.comment-content p');
    const originalText = commentContent.textContent;

    // Zamień tekst na pole edycji z przyciskami zapisz i anuluj
    commentContent.innerHTML = `
        <input type="text" value="${originalText}">
        <div class="edit-buttons">
            <button class="save-comment-btn">Zapisz</button>
            <button class="cancel-comment-btn">Anuluj</button>
        </div>
    `;

    // Obsługa przycisku zapisz
    const saveButton = comment.querySelector('.save-comment-btn');
    saveButton.addEventListener('click', function () {
        const newContent = comment.querySelector('input').value.trim();
        if (newContent) {
            updateComment(commentId, newContent);
            commentContent.innerHTML = newContent;
        } else {
            showNotification('Treść komentarza nie może być pusta', 'error');
        }
    });

    // Obsługa przycisku anuluj
    const cancelButton = comment.querySelector('.cancel-comment-btn');
    cancelButton.addEventListener('click', function () {
        commentContent.innerHTML = originalText;
    });
}

/**
 * Procedura edytowania posta
 * @param {number} postId - ID posta
 */
function editPostProcedure(postId) {
    const postCard = document.querySelector(`.post-card[data-post-id="${postId}"]`);
    const postContent = postCard.querySelector('.post-content p');
    const originalText = postContent.innerHTML;

    // Zamień tekst na pole edycji z przyciskami zapisz i anuluj
    postContent.innerHTML = `
        <textarea style="width: 100%; min-height: 100px; padding: 8px; margin-bottom: 8px; border: 1px solid #ddd; border-radius: 4px;">${originalText}</textarea>
        <div class="edit-buttons">
            <button class="save-post-btn">Zapisz</button>
            <button class="cancel-post-btn">Anuluj</button>
        </div>
    `;

    // Obsługa przycisku zapisz
    const saveButton = postCard.querySelector('.save-post-btn');
    saveButton.addEventListener('click', function () {
        const newContent = postCard.querySelector('textarea').value.trim();
        if (newContent) {
            updatePost(postId, newContent);
            postContent.innerHTML = newContent;
        } else {
            showNotification('Treść posta nie może być pusta', 'error');
        }
        filterPosts("recent"); // Odświeżenie postów po edytowaniu
    });

    // Obsługa przycisku anuluj
    const cancelButton = postCard.querySelector('.cancel-post-btn');
    cancelButton.addEventListener('click', function () {
        postContent.innerHTML = originalText;
    });
}

/**
 * Aktualizuje post na serwerze
 * @param {number} postId - ID posta
 * @param {string} newContent - Nowa treść posta
  */
function updatePost(postId, newContent) {
    console.log(`Edycja posta o ID: ${postId}, nowa treść: ${newContent}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
            let result = JSON.parse(this.responseText);
            if (result.status === "success") {
                showNotification('Post został zaktualizowany', 'success');
            }
            else {
                showNotification('Nie udało się zaktualizować posta', 'error');
            }
        }
    }

    xmlhttp.open("POST", "../../api/social-page/edit_post.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`postId: ${postId}, newContent: ${newContent}`);
    xmlhttp.send("postId=" + encodeURIComponent(postId) + "&newContent=" + encodeURIComponent(newContent));
}

/**
 * Usuwa post z serwera
 * @param {number} postId - ID postu
 */
function deletePost(postId) {
    console.log(`Usuwanie postu o ID: ${postId}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.responseText);
            let result = JSON.parse(this.responseText);
            if (result.status === "success") {
                showNotification('Post został usunięty', 'success');
            }
            else {
                showNotification('Nie udało się usunąć posta', 'error');
            }
        }
    }

    xmlhttp.open("POST", "../../api/social-page/delete_post.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(`postId: ${postId}`);
    xmlhttp.send("postId=" + encodeURIComponent(postId));
}


/**
 * Pokazuje menu opcji postu
 * @param {HTMLElement} element - Element opcji postu
 * @param {number} postId - ID postu
 */
function showPostMenu(element, postId) {
    // Sprawdź, czy menu już istnieje
    const existingMenu = document.querySelector('.post-options-menu');
    if (existingMenu) {
        existingMenu.remove();
    }

    // Utwórz nowe menu
    const menu = document.createElement('div');
    menu.className = 'post-options-menu';
    menu.innerHTML = `
        <ul>
            <li data-action="save"><i class="fas fa-bookmark"></i> Zapisz post</li>
            <li data-action="report"><i class="fas fa-flag"></i> Zgłoś post</li>
            <li data-action="hide"><i class="fas fa-eye-slash"></i> Ukryj post</li>
            <li data-action="edit"><i class="fas fa-edit"></i> Edytuj post</li>
            <li data-action="privileges"><i class="fas fa-lock"></i> Zmień uprawnienia</li>
            <li data-action="delete"><i class="fas fa-trash-alt"></i> Usuń post</li>
        </ul>
    `;

    // Dodaj style do menu
    menu.style.position = 'absolute';
    menu.style.backgroundColor = 'white';
    menu.style.borderRadius = '8px';
    menu.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    menu.style.padding = '5px 0';
    menu.style.zIndex = '1000';

    // Dodaj style do elementów listy
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        .post-options-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .post-options-menu li {
            padding: 8px 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .post-options-menu li:hover {
            background-color: #f0f2f5;
        }
    `;
    document.head.appendChild(styleSheet);

    // Dodaj menu do dokumentu
    document.body.appendChild(menu);

    // Ustaw pozycję menu względem elementu
    const rect = element.getBoundingClientRect();
    menu.style.top = `${rect.bottom + window.scrollY}px`;
    menu.style.left = `${rect.left + window.scrollX}px`;

    // Dodaj obsługę kliknięć
    menu.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', function () {
            const action = this.dataset.action;
            handlePostAction(postId, action);
            menu.remove();
        });
    });

    // Zamknij menu po kliknięciu poza nim
    document.addEventListener('click', function closeMenu(e) {
        if (!menu.contains(e.target) && e.target !== element) {
            menu.remove();
            document.removeEventListener('click', closeMenu);
        }
    });
}

/**
 * Obsługuje akcje na poście
 * @param {number} postId - ID postu
 * @param {string} action - Akcja do wykonania
 */
function handlePostAction(postId, action) {
    console.log(`Akcja ${action} dla postu ${postId}`);

    switch (action) {
        case 'save':
            showNotification('Post został zapisany', 'success');
            break;
        case 'report':
            showNotification('Post został zgłoszony', 'info');
            break;
        case 'hide':
            // Ukryj post w interfejsie
            const postElement = document.querySelector(`.post-card[data-post-id="${postId}"]`);
            if (postElement) {
                postElement.style.display = 'none';
                showNotification('Post został ukryty', 'success');
            }
            break;
        case 'edit':
            // Edytuj post - procedura
            editPostProcedure(postId);
            showNotification('Edycja posta', 'info');
            break;
        case 'privileges':
            // Zmień uprawnienia (w prawdziwej implementacji otworzyłby się formularz zmiany uprawnień)
            showNotification('Zmiana uprawnień posta', 'info');
            break;
        case 'delete':
            // Usuń post z serwera
            deletePost(postId);
            // Usuń post z interfejsu
            const postToDelete = document.querySelector(`.post-card[data-post-id="${postId}"]`);
            if (postToDelete) {
                postToDelete.remove();
            }
            break;
    }
}

/*
 * Inicjalizacja filtrów postów
 */
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Usuń klasę active ze wszystkich przycisków
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Dodaj klasę active do klikniętego przycisku
            this.classList.add('active');

            // Pobierz typ filtra
            const filterType = this.dataset.filter;

            // W prawdziwej implementacji: Załaduj posty na podstawie filtra
            filterPosts(filterType);
        });
    });
}

/**
 * Filtruje posty na podstawie wybranego filtra
 * @param {string} filterType - Typ filtra
 */
function filterPosts(filterType) {
    console.log(`Filtrowanie postów: ${filterType}`);

    showNotification(`Filtruję posty: ${filterType}`, 'info');

    // Symulacja opóźnienia ładowania
    const postsContainer = document.querySelector('.posts-container');
    postsContainer.style.opacity = '0.5';

    loadPosts(filterType); // Załaduj posty na podstawie filtra

    postsContainer.style.opacity = '1';
    showNotification(`Załadowano posty: ${filterType}`, 'success');
}

/*
 * Inicjalizacja przycisku załaduj więcej
 */
function initializeLoadMore() {
    const loadMoreBtn = document.getElementById('load-more-btn');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            // Zablokuj przycisk na czas ładowania
            this.disabled = true;
            this.textContent = 'Ładowanie...';

            // Symulacja ładowania nowych postów
            setTimeout(() => {
                loadMorePosts();

                // Odblokuj przycisk
                this.disabled = false;
                this.textContent = 'Załaduj więcej';
            }, 1000);
        });
    }
}

/*
* Funkcja ładowania postów po kryterium
*/
function loadPosts(criterion = "all") {
    // Lista postów zostanie załadowana z serwera z pliku posts_category_filter.php:
    console.log(`Ładowanie postów po kryterium: ${criterion}`);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Odpowiedź serwera:', this.response);

            // Tutaj można przetworzyć odpowiedź i zaktualizować UI

            let result = JSON.parse(this.response);
            if (result.status == "error") {
                showNotification(result.message, 'error');
                return;
            }
            console.log(result.message);

            let posts = result.posts;
            let current_user = result.current_user;
            console.log("posty: " + posts);

            const postsContainer = document.querySelector('.posts-container');
            postsContainer.innerHTML = ''; // Wyczyść kontener przed dodaniem nowych postów

            // Dla każdego posta
            for (let postStructure of posts) {
                let post = postStructure.post;
                let is_liked_by_current_user = postStructure.is_liked_by_current_user;
                let comments = postStructure.comments;

                // Stwórz element posta
                const postCard = document.createElement('div');
                postCard.className = 'post-card';
                postCard.dataset.postId = post.id;

                // Dodaj komentarze do posta
                var commentsHTML = '';
                for (let comment of comments) {
                    commentsHTML += `
                        <div class="comment" data-comment-id="${comment.id}">
                            <img src="${comment.commenter_avatar}" alt="Avatar użytkownika">
                            <div class="comment-content">
                                <h5>${comment.commenter_login}</h5>
                                <p>${comment.content}</p>
                                <div class="comment-actions">
                                    <span class="comment-time">${comment.creation_date}</span>
                                    <button class="comment-like-btn${comment.is_liked_by_current_user ? " active" : ""}" data-liked="${comment.is_liked_by_current_user ? "true" : "false"}"><i class="${comment.is_liked_by_current_user ? "fas": "far"} fa-thumbs-up"></i> <span class="likes-count">${comment.like_count > 0 ? comment.like_count + " " : ""}</span>Lubię to</button>
                                    <button class="comment-reply-btn"><i class="fas fa-reply"></i> Odpowiedz</button>
                                    ${ current_user.id == comment.comment_author_id ?
                                    `<button class="comment-edit-btn"><i class="fas fa-edit"></i> Edytuj</button>
                                    <button class="comment-delete-btn"><i class="fas fa-trash"></i> Usuń</button>` : ``}
                                </div>
                            </div>
                        </div>`;
                        console.log(current_user.id + " " + comment.comment_author_id);
                }

                if(post.is_commentable) {
                postCard.innerHTML = `
        <div class="post-header" data-post-id="${post.id}">
            <div class="post-author">
                <a class="post-link" href="../profile-page/index.php?user_name=${post.author_login}">    
                    <img src="${post.author_avatar}" alt="Avatar użytkownika">
                        <div class="author-info">
                        <h4>${post.author_login}</h4>
                    </a>
                    <a class="post-link" href="../post-page/index.php?post_id=${post.id}">    
                        <span class="privacy-status">
                        <i class="${post.access_level === 'public' ? 'fas fa-globe' : (post.access_level === 'friends' ? 'fas fa-users' : 'fas fa-lock')}"></i>
                        ${post.access_level === 'public' ? 'Publiczny' : (post.access_level === 'friends' ? 'Znajomi' : 'Prywatny')}
                        </span>
                        <span class="post-time">${post.last_modification_date}</span>
                    </a>
                </div>
                </a>
            </div>
            <div class="post-options"><i class="fas fa-ellipsis-h"></i></div>
        </div>
        <div class="post-content"><p>${post.content}</p></div>
        <div class="post-interactions">
            <div class="interaction-stats">
                <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>${post.like_count}</span></span>
                <span class="comments-count">${post.comment_count} komentarzy</span>
            </div>
            <div class="interaction-buttons">
                ${is_liked_by_current_user ?
                        '<button class="like-btn like-btn-currently-liked" data-liked="true"><i class="fas fa-thumbs-up"></i> Lubię to</button>' :
                        '<button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>'
                    }
                <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
                <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
            </div>
        </div>

                     <div class="comments-section">

                         <div class="comments-container">
                         
                    `+ commentsHTML + `

                         </div>

                        <div class="add-comment">
                            <img src="${current_user.avatar}" alt="Twój avatar">
                            <input type="text" placeholder="Napisz komentarz...">
                            <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
                         </div>

                     </div>`

                    ;
                } 
                else {
                    postCard.innerHTML = `
                <div class="post-header" data-post-id="${post.id}">
                    <div class="post-author">
                <a class="post-link" href="../profile-page/index.php?user_name=${post.author_login}">    
                    <img src="${post.author_avatar}" alt="Avatar użytkownika">
                        <div class="author-info">
                        <h4>${post.author_login}</h4>
                    </a>
                    <a class="post-link" href="../post-page/index.php?post_id=${post.id}">    
                        <span class="privacy-status">
                        <i class="${post.access_level === 'public' ? 'fas fa-globe' : (post.access_level === 'friends' ? 'fas fa-users' : 'fas fa-lock')}"></i>
                        ${post.access_level === 'public' ? 'Publiczny' : (post.access_level === 'friends' ? 'Znajomi' : 'Prywatny')}
                        </span>
                        <span class="post-time">${post.last_modification_date}</span>
                    </a>
                </div>
                </a>
            </div>
                    <div class="post-options"><i class="fas fa-ellipsis-h"></i></div>
                </div>
                <div class="post-content"><p>${post.content}</p></div>
                <div class="post-interactions">
                    <div class="interaction-stats">
                        <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>${post.like_count}</span></span>
                    </div>
                    <div class="interaction-buttons">
                        ${is_liked_by_current_user ?
                                '<button class="like-btn like-btn-currently-liked" data-liked="true"><i class="fas fa-thumbs-up"></i> Lubię to</button>' :
                                '<button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>'
                            }
                        <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
                    </div>
                </div>`;
                }

                // Dodaj post do kontenera
                postsContainer.appendChild(postCard);

            }
            initializePostInteractions(); // Inicjalizacja interakcji z postami (lajki, komentarze) dla wszystkich postów4

            // --------------
            // --------------
            // --------------


        }
    }
    xmlhttp.open("POST", "../../api/social-page/posts_category_filter.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("criterion=" + encodeURIComponent(criterion));

}

/**
 * Ładuje więcej postów
 */
// function loadMorePosts() {
//     const postsContainer = document.querySelector('.posts-container');

//     // W rzeczywistej implementacji posty byłyby pobierane z serwera
//     // Na potrzeby przykładu dodajemy nowy post do interfejsu
//     const newPost = document.createElement('div');
//     newPost.className = 'post-card';
//     newPost.dataset.postId = Math.floor(Math.random() * 1000) + 4; // Losowe ID postu

//     newPost.innerHTML = `
//         <div class="post-header">
//             <div class="post-author">
//                 <img src="../../assets/img/avatars/user9.png" alt="Avatar użytkownika">
//                 <div class="author-info">
//                     <h4>Karolina Malinowska</h4>
//                     <span class="post-time">Przed chwilą</span>
//                 </div>
//             </div>
//             <div class="post-options">
//                 <i class="fas fa-ellipsis-h"></i>
//             </div>
//         </div>
//         <div class="post-content">
//             <p>Właśnie odkryłam świetne miejsce na weekend za miastem! Kto ma ochotę na wycieczkę w ten weekend?</p>
//         </div>
//         <div class="post-interactions">
//             <div class="interaction-stats">
//                 <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>0</span></span>
//                 <span class="comments-count">0 komentarzy</span>
//             </div>
//             <div class="interaction-buttons">
//                 <button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>
//                 <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
//                 <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
//             </div>
//         </div>
//         <div class="comments-section">
//             <div class="comments-container"></div>
//             <div class="add-comment">
//                 <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
//                 <input type="text" placeholder="Napisz komentarz...">
//                 <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
//             </div>
//         </div>
//     `;

//     // Dodaj nowy post do kontenera
//     postsContainer.appendChild(newPost);

//     // Dodaj obsługę zdarzeń do nowego postu
//     const newLikeBtn = newPost.querySelector('.like-btn');
//     newLikeBtn.addEventListener('click', function() {
//         const postId = this.closest('.post-card').dataset.postId;
//         const isLiked = this.dataset.liked === 'true';
//         const likeCountElement = this.closest('.post-card').querySelector('.likes-count span');
//         let likeCount = parseInt(likeCountElement.textContent);

//         if (isLiked) {
//             this.dataset.liked = 'false';
//             this.innerHTML = '<i class="far fa-thumbs-up"></i> Lubię to';
//             this.classList.remove('active');
//             likeCount--;
//         } else {
//             this.dataset.liked = 'true';
//             this.innerHTML = '<i class="fas fa-thumbs-up"></i> Lubię to';
//             this.classList.add('active');
//             likeCount++;
//         }

//         likeCountElement.textContent = likeCount;
//         updateLikeStatus(postId, !isLiked);
//     });

//     const newCommentBtn = newPost.querySelector('.comment-btn');
//     newCommentBtn.addEventListener('click', function() {
//         const commentsSection = this.closest('.post-card').querySelector('.comments-section');
//         if (commentsSection.style.display === 'none') {
//             commentsSection.style.display = 'block';
//             commentsSection.querySelector('input').focus();
//         } else {
//             commentsSection.style.display = 'none';
//         }
//     });

//     const newSubmitCommentBtn = newPost.querySelector('.submit-comment-btn');
//     newSubmitCommentBtn.addEventListener('click', function() {
//         const postCard = this.closest('.post-card');
//         const postId = postCard.dataset.postId;
//         const commentInput = this.closest('.add-comment').querySelector('input');
//         const commentText = commentInput.value.trim();

//         if (commentText) {
//             addCommentToUI(postCard, commentText);
//             submitComment(postId, commentText);
//             commentInput.value = '';
//         }
//     });

//     const newCommentInput = newPost.querySelector('.add-comment input');
//     newCommentInput.addEventListener('keypress', function(e) {
//         if (e.key === 'Enter') {
//             const postCard = this.closest('.post-card');
//             const postId = postCard.dataset.postId;
//             const commentText = this.value.trim();

//             if (commentText) {
//                 addCommentToUI(postCard, commentText);
//                 submitComment(postId, commentText);
//                 this.value = '';
//             }
//         }
//     });

//     const newPostOptions = newPost.querySelector('.post-options');
//     newPostOptions.addEventListener('click', function() {
//         const postId = this.closest('.post-card').dataset.postId;
//         showPostMenu(this, postId);
//     });

//     // Powiadomienie o załadowaniu nowych postów
//     showNotification('Załadowano nowe posty', 'success');
// }

/*
 * Inicjalizacja sugestii znajomych
 */
function initializeFriendSuggestions() {
    const addFriendButtons = document.querySelectorAll('.add-friend-btn');

    addFriendButtons.forEach(button => {
        button.addEventListener('click', function () {
            const suggestionCard = this.closest('.suggestion-card');
            const friendName = suggestionCard.querySelector('h4').textContent;

            // Zmień ikonę i styl przycisku
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.style.backgroundColor = 'var(--primary-color)';
            this.style.color = 'white';
            this.disabled = true;

            // Pokaż powiadomienie
            showNotification(`Wysłano zaproszenie do ${friendName}`, 'success');

            // W prawdziwej implementacji: Wysłanie zaproszenia do znajomego
            console.log(`Wysłano zaproszenie do ${friendName}`);
        });
    });
}


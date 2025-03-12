// ---------------------------
// Scroll to the bottom of the chat messages
// ---------------------------
document.addEventListener("DOMContentLoaded", function () {
    var chatMessages = document.getElementById("chat-messages");
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

// ---------------------------
// Gestion de l'envoi du formulaire
// ---------------------------
document.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("BoutonSoumettre").click();
    }
});

// ---------------------------
// Lorsqu'un fichier est sélectionné
// ---------------------------
$("#fichierInput").on("change", function () {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var fileExtension = input.files[0].name
                .split(".")
                .pop()
                .toLowerCase();
            var isImage = ["jpg", "jpeg", "png", "gif"].includes(fileExtension);
            var previewContent = "";

            if (isImage) {
                previewContent =
                    '<div style="position: relative; display: inline-block;">' +
                    '<img src="' +
                    e.target.result +
                    '" alt="Aperçu de l\'image sélectionnée" class="preview-img">' +
                    '<button id="cancel-preview" ' +
                    'style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.7); border: none; color: white; font-size: 20px; line-height: 20px; width: 25px; height: 25px; border-radius: 50%; cursor: pointer;">' +
                    "&times;" +
                    "</button>" +
                    "</div>";
            } else {
                previewContent =
                    '<div style="position: relative; display: inline-block;">' +
                    '<i class="fa fa-folder" style="font-size: 50px;"></i>' +
                    '<button id="cancel-preview" ' +
                    'style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.7); border: none; color: white; font-size: 20px; line-height: 20px; width: 25px; height: 25px; border-radius: 50%; cursor: pointer;">' +
                    "&times;" +
                    "</button>" +
                    "</div>";
            }

            $("#preview-container").html(previewContent);
        };
        reader.readAsDataURL(input.files[0]);
    }
});

// Lorsqu'on clique sur le bouton "X"
$(document).on("click", "#cancel-preview", function () {
    $("#preview-container").empty();
    $("#fichierInput").val("");
});

// ---------------------------
// Gestion des emojis
// ---------------------------
document.addEventListener("DOMContentLoaded", async function () {
    const emojiButton = document.getElementById("emoji-btn");
    const emojiPicker = document.getElementById("emoji-picker-container");
    let isPickerVisible = false;
    let allEmojis = [];

    // 📌 Création du conteneur de la liste des emojis avec scroll
    const emojiList = document.createElement("div");
    emojiList.id = "emoji-list";
    emojiList.style.maxHeight = "250px"; // Hauteur max avec scroll
    emojiList.style.overflowY = "auto";
    emojiList.style.padding = "10px";
    emojiList.style.display = "grid";
    emojiList.style.gridTemplateColumns =
        "repeat(auto-fill, minmax(30px, 1fr))"; // Grille d'icônes
    emojiList.style.gap = "5px";
    emojiList.style.borderTop = "1px solid #ccc";

    emojiPicker.appendChild(emojiList);

    // 🏗 Barre de recherche
    const searchContainer = document.createElement("div");
    searchContainer.style.padding = "5px";
    const searchInput = document.createElement("input");
    searchInput.type = "text";
    searchInput.placeholder = translations.emojiSearch;
    searchInput.style.width = "100%";
    searchInput.style.padding = "5px";
    searchInput.style.border = "1px solid #ccc";
    searchInput.style.borderRadius = "5px";
    searchInput.style.color = "black";
    searchContainer.appendChild(searchInput);
    emojiPicker.prepend(searchContainer);

    // 🛠 Charger les emojis depuis l'API
    async function loadEmojis() {
        try {
            let response = await fetch(
                "https://emoji-api.com/emojis?access_key=fbb83db9397a3267754dfb56209380fd8df13813"
            );
            let emojis = await response.json();
            allEmojis = emojis; // Stocker tous les emojis pour la recherche
            displayEmojis(allEmojis);
        } catch (error) {
            console.error("Erreur de chargement des emojis :", error);
        }
    }

    // 🔄 Fonction d'affichage des emojis
    function displayEmojis(emojis) {
        emojiList.innerHTML = ""; // Nettoyer la liste
        emojis.forEach((emoji) => {
            const emojiItem = document.createElement("span");
            emojiItem.textContent = emoji.character;
            emojiItem.classList.add("emoji-item");
            emojiItem.style.cursor = "pointer";
            emojiItem.style.fontSize = "24px";
            emojiItem.style.margin = "5px";

            // 📌 Ajouter l'emoji au champ texte
            emojiItem.addEventListener("click", () => {
                document.getElementById("message").value += emoji.character;
            });

            emojiList.appendChild(emojiItem);
        });
    }

    // 🎯 Barre de recherche d'emoji
    searchInput.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        const filteredEmojis = allEmojis.filter((emoji) =>
            emoji.unicodeName.toLowerCase().includes(searchTerm)
        );
        displayEmojis(filteredEmojis);
    });

    // Charger les emojis au chargement de la page
    await loadEmojis();

    // 🎭 Affichage du picker
    emojiButton.addEventListener("click", (e) => {
        e.stopPropagation();
        if (!isPickerVisible) {
            emojiPicker.style.display = "block";
            setTimeout(() => {
                emojiPicker.classList.add("active");
                isPickerVisible = true;
            }, 10);
        } else {
            emojiPicker.classList.remove("active");
            setTimeout(() => {
                emojiPicker.style.display = "none";
                isPickerVisible = false;
            }, 200);
        }
    });

    // ❌ Fermer l'emoji picker quand on clique ailleurs
    document.addEventListener("click", (e) => {
        if (!emojiPicker.contains(e.target) && e.target !== emojiButton) {
            emojiPicker.classList.remove("active");
            setTimeout(() => {
                emojiPicker.style.display = "none";
                isPickerVisible = false;
            }, 200);
        }
    });
});

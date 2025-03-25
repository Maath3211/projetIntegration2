
//console.log("Chargement du script chat.js");

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
    if (!emojiButton) {
        //console.error("Le bouton emoji avec l'ID 'emoji-btn' est introuvable.");
        return;
    }
    //console.log("Bouton emoji trouvé :", emojiButton);

    const emojiPicker = document.getElementById("emoji-picker-container");
    if (!emojiPicker) {
        //console.error("Le conteneur emoji avec l'ID 'emoji-picker-container' est introuvable.");
        return;
    }
    //console.log("Conteneur emoji trouvé :", emojiPicker);

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
    searchInput.placeholder = "Rechercher un emoji...";
    searchInput.style.width = "100%";
    searchInput.style.padding = "5px";
    searchInput.style.border = "1px solid #ccc";
    searchInput.style.borderRadius = "5px";
    searchInput.style.color = "black";
    searchContainer.appendChild(searchInput);
    emojiPicker.prepend(searchContainer);

    // 🛠 Charger les emojis depuis l'API
    async function loadEmojis() {
        //console.log("Chargement des emojis...");
        try {
            allEmojis = [
                            { character: "😀", unicodeName: "visage souriant" },
                            { character: "😁", unicodeName: "visage rayonnant avec des yeux souriants" },
                            { character: "😂", unicodeName: "visage avec des larmes de joie" },
                            { character: "🤣", unicodeName: "rouler par terre de rire" },
                            { character: "😊", unicodeName: "visage souriant avec des yeux souriants" },
                            { character: "😎", unicodeName: "visage souriant avec des lunettes de soleil" },
                            { character: "😍", unicodeName: "visage souriant avec des yeux en forme de cœur" },
                            { character: "😜", unicodeName: "visage faisant un clin d'œil avec la langue" },
                            { character: "😡", unicodeName: "visage en colère" },
                            { character: "😭", unicodeName: "visage pleurant bruyamment" },
                            { character: "😱", unicodeName: "visage criant de peur" },
                            { character: "❤️", unicodeName: "cœur rouge" },
                            { character: "🔥", unicodeName: "feu" },
                            { character: "🎉", unicodeName: "confettis" },
                            { character: "💯", unicodeName: "cent points" },
                        ];
            //console.log("✅ Emojis chargés localement :", allEmojis);
            displayEmojis(allEmojis);
        } catch (error) {
            //console.error("Erreur de chargement des emojis :", error);
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
        //console.log("Bouton emoji cliqué"); // Vérifiez si le clic est détecté
        if (!isPickerVisible) {
            emojiPicker.style.display = "block";
            setTimeout(() => {
                emojiPicker.classList.add("active");
                isPickerVisible = true;
                //console.log("Emoji picker affiché"); // Vérifiez si le picker est affiché
            }, 10);
        } else {
            emojiPicker.classList.remove("active");
            setTimeout(() => {
                emojiPicker.style.display = "none";
                isPickerVisible = false;
                //console.log("Emoji picker masqué"); // Vérifiez si le picker est masqué
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

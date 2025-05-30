// #region Dane galerii
const galleryData = [
    {
        id: 1,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg", // Zastąp własnymi ścieżkami do obrazów
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Górski krajobraz",
        category: "natura",
        description: "Piękny górski krajobraz z ośnieżonymi szczytami"
    },
    {
        id: 2,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Nowoczesny budynek",
        category: "architektura",
        description: "Współczesne arcydzieło architektury w centrum miasta"
    },
    {
        id: 3,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Zachód słońca na plaży",
        category: "natura",
        description: "Spektakularny zachód słońca nad tropikalną plażą"
    },
    {
        id: 4,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Historyczna świątynia",
        category: "podroze",
        description: "Starożytna świątynia z misternymi kamiennymi rzeźbami"
    },
    {
        id: 5,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Panorama miasta",
        category: "architektura",
        description: "Panoramiczny widok na panoramę miasta o zmierzchu"
    },
    {
        id: 6,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Leśna ścieżka",
        category: "natura",
        description: "Spokojna ścieżka przez jesienny las"
    },
    {
        id: 7,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Kanały Wenecji",
        category: "podroze",
        description: "Malownicze kanały Wenecji we Włoszech"
    },
    {
        id: 8,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Wieżowiec",
        category: "architektura",
        description: "Wysoki szklany wieżowiec odbijający chmury"
    },
    {
        id: 9,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Tropikalny wodospad",
        category: "natura",
        description: "Ukryty wodospad w bujnym tropikalnym lesie"
    },
    {
        id: 10,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Ulica handlowa",
        category: "podroze",
        description: "Tętniąca życiem ulica targowa z kolorowymi straganami"
    },
    {
        id: 11,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Krajobraz pustyni",
        category: "natura",
        description: "Rozległy krajobraz pustyni z wydmami"
    },
    {
        id: 12,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Stary most",
        category: "architektura",
        description: "Kamienny most z wielowiekową historią"
    }
];
// #endregion
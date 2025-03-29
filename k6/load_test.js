import http from 'k6/http';
import { check } from "k6";

export let options = {
    stages: [
        // Ramp-up from 1 to 5 VUs in 5s
        { duration: "5s", target: 5 },
        // 10 VUs for 10s
        { duration: "10s", target: 10 },
        // 50 VUs for 10s
        { duration: "10s", target: 50 },
        // Ramp-down from 50 to 100 VUs for 5s
        { duration: "5s", target: 10 },
        // Ramp-down from 5 to 0 VUs for 5s
        { duration: "5s", target: 5 }
    ]
};


/*
export default function () {
    // 1. Affichage de la liste des livres
    let listResponse = http.get('http://tpmongo-php:80/', {
        headers: { Accept: 'application/json' }
    });
    check(listResponse, {
        'liste des livres status is 200': (r) => r.status === 200,
        'liste contient books': (r) => r.body.includes('books')
    });

    // 2. Affichage de la page 30 (pagination)
    let page30Response = http.get('http://tpmongo-php:80/?page=30', {
        headers: { Accept: 'application/json' }
    });
    check(page30Response, {
        'page 30 status is 200': (r) => r.status === 200
        //'body contient page 30': (r) => r.body.includes('page 30')
    });

    // 3. Consultation des détails d’un livre (ID 1, à adapter si nécessaire)
    let detailsResponse = http.get('http://tpmongo-php:80/books/1', {
        headers: { Accept: 'application/json' }
    });
    check(detailsResponse, {
        'details du livre status is 200': (r) => r.status === 200
        //'body contient title': (r) => r.body.includes('title')
    });

    // 4. Retour à la liste des livres
    let returnResponse = http.get('http://tpmongo-php:80/', {
        headers: { Accept: 'application/json' }
    });
    check(returnResponse, {
        'retour liste status is 200': (r) => r.status === 200
        //'body contient books': (r) => r.body.includes('books')
    });

    // 5. Ajout d’un nouveau livre
    let newBookPayload = JSON.stringify({
        title: 'Nouveau Livre',
        author: 'Auteur Exemple',
        description: 'Description du nouveau livre.'
    });
    let addResponse = http.post('http://tpmongo-php:80/books', newBookPayload, {
        headers: { 'Content-Type': 'application/json' }
    });
    check(addResponse, {
        'ajout livre status is 201': (r) => r.status === 201
        //'body contient Nouveau Livre': (r) => r.body.includes('Nouveau Livre')
    });

    // 6. Consultation du livre ajouté
    // On suppose que la réponse à l'ajout retourne l'ID (dans "id" ou "_id")
    let addedBookId;
    try {
        let addData = JSON.parse(addResponse.body);
        addedBookId = addData.id || addData._id || '101';
    } catch (e) {
        addedBookId = '101';
    }
    let consultResponse = http.get(`http://tpmongo-php:80/books/${addedBookId}`, {
        headers: { Accept: 'application/json' }
    });
    check(consultResponse, {
        'consultation livre ajoute status is 200': (r) => r.status === 200
        //'body contient Nouveau Livre': (r) => r.body.includes('Nouveau Livre')
    });

    // 7. Suppression du livre ajouté
    let deleteResponse = http.del(`http://tpmongo-php:80/books/${addedBookId}`, null, {
        headers: { Accept: 'application/json' }
    });
    check(deleteResponse, {
        'suppression du livre status is 204': (r) => r.status === 204
    });
};*/

export default function () {
    // 1. Affichage de la liste des livres
    var response = http.get("http://tpmongo-php:80/", {headers: {Accept: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });

    // 2. Affichage de la page 30 (pagination)
    var response = http.get("http://tpmongo-php:80/?page=30/", {headers: {Accept: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });

    // 3. Consultation des détails d’un livre (ID 1, à adapter si nécessaire)
    var response = http.get("http://tpmongo-php:80/get.php?id=67e7070b2c6b6f576cc5c80f", {headers: {Accept: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });

    // // 4. Retour à la liste des livres
    // var response = http.get("http://tpmongo-php:80/?search=&page=8", {headers: {Accept: "application/json"}});
    // check(response, { "status is 200": (r) => r.status === 200 });


    // // 5. Création d'un livre
    // let formData = {
    //     title: "Nouveau Livre",
    //     author: "Auteur Test",
    //     century: 21
    // };
    // // // let createResponse = http.post("http://tpmongo-php:80/create.php", newBook, {
    // // //     headers: {
    // // //         "Content-Type": "application/json",
    // // //         "Accept": "application/json"
    // // //     }
    // // // });

    // let createResponse = http.post("http://tpmongo-php:80/create.php", formData, {
    //     headers: {
    //         "Content-Type": "application/x-www-form-urlencoded", // Envoi sous forme de formulaire
    //         "Accept": "application/json"
    //     }
    // });

    
    // check(createResponse, {
    //     "status is 200 or 201": (r) => r.status === 200 || r.status === 201
    // });

    // // 5. Récuperation de l'id du livre ajouté
    // let responseBody = createResponse.json();
    // let bookId = responseBody.id;  
    
    // // 6. Consultation des détails du livre ajouté
    // var response = http.get(`http://tpmongo-php:80/get.php?id=${bookId}`, {headers: {Accept: "application/json"}});
    // check(response, { "status is 200": (r) => r.status === 200 });
    
    // // 7. Suppression du livre ajouté
    // let deleteResponse = http.get(`http://tpmongo-php:80/delete.php?id=${bookId}`);
    // check(deleteResponse, { "suppression status is 302": (r) => r.status === 302 });

    // var response = http.get("http://tpmongo-php:80/", {headers: {Accept: "application/json"}});
    // check(response, { "status is 200": (r) => r.status === 200 });
};

function extractBookId(response) {
    try {
        let jsonRes = JSON.parse(response.body);
        return jsonRes.insertedId || null;
    } catch (error) {
        return null;
    }
}

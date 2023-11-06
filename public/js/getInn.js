async function suggestInn(
    surname,
    name,
    patronymic,
    birthdate,
    docnumber,
    docdate
) {
    const url = "/api/getInn";
    const data = {
        fam: surname,
        nam: name,
        otch: patronymic,
        bdate: birthdate,
        docno: docnumber,
        docdt: docdate,
    };
    response = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
        },
        body: JSON.stringify(data)
    });
    if (response.ok) {
        let json = await response.json();
        return json;
    } else {
        console.log("Ошибка HTTP: " + response.status);
        return null;
    }
}

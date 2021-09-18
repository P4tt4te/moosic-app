envoi();

function envoi() {
    let param = new FormData();
    param.append("nomRadio", 'test');
    fetch('info.php', {
      method: 'POST',
      body:param,
      headers: {
        'Content-type': 'application/json; charset=UTF-8'
      }
    }).then(function (response) {
      return response.json();
    }).then(function (object) {
      console.log(object);
    }).catch(function (error) {
      console.error('bugEnvoi');
      console.error(error);
    })
  }
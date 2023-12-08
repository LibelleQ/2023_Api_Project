const button = document.querySelector("#todo-create");

const textArea = document.querySelector("#todo-desc");

button.addEventListener("click", () => {
  fetch("http://localhost:8081/index.php/todos", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      description: textArea.value,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.href = "/";
      } else {
        alert(data.message);
      }
    });
});

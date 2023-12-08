# Correction

Ci-jointe la correction pour l'exercice sur les API qui consistait en faire une APi pour les To-Dos.

L'implémentation représente une façon de répondre aux consignes avec un bon comprimis entre la simplicité et la rpopreté, si l'on s'en tient à ce que l'on a appris jusqu'à maintenant.

Cette implémentation respècte le modèle MVC dans la mesure où il n'y a pas de "vue" étant donné qu'il s'agit d'un API simple.

## Solution

La solution se sépare en plusieur parties :

### Le routeur

Le routeur est le composant du controlleur qui ici gère la dispertion des requêtes vers la ressource demandée.

Il lis l'URL et redirige en fonction du chemin(`path`) de la requête reçue.

### Le controlleur des To-Dos

Une Dans le cas ou le chemin demandé est `todos`, alors la requête est dispatchée vers le controler des To-Dos.

Il lit la méthode HTTP et regarde s'il y a un ID fournis dans le chemin de la ressource afin de dispatcher ensuite les demandes à la couche Modèle.

Lorsqu'il passe les demandes à la couche modèle, il doit abstraire la couche réseau: Il doit passer des données de types et de formats indépendants de la nature des requêtes qu'il reçoit. Le contexte HTTP ne doit pas sortir du controlleur.

### Les fonctions de retour

Deux cas de figures se distinguent :

- On doit renvoyer du contenu récupéré dans la base de donnée
- On n'a pas de contenu et on doit plutot renvoyer un message.

On a une fonction pour chacun de ces cas avecu n moyen de régler le message où le contenu.
Cela permet de centraliser les "echo" et la pose des headers HTTP, qui doivent n'être faits qu'une fois.

De plus, cela permet un code qui respecte le concept DRY: Don't Repeat Yourself.

### La gestion d'erreurs

Ici, afin de gérer les erreurs, on fait remonter les erreurs au controlleur, qui doit les catcher.

Lorsque l'on catch une exception, le controlleur joue le rôle de la traduction de l'exception native à la réponse HTTP.

Une API doit être indépendante de la technologie dans laquelle elle est codée, et ne doit que répondre à des contraintes établies d'échanges entre deux programmes.

Ici, on transforme les exceptions en messages que l'on renvoie quand une requête n'a pas aboutie.

Encore une fois, c'est donc le controlleur qui assure la traduction du contexte "web/HTTP" en de la logique pure.

En soit, une exception déscendante de l'Exception de base suffit avec PHP car elle peut porter un message et un code d'erreur, ce qui correspond à ce que l'on doit renvoyer.

### Modèle TodoModel

La classe TodoModel correspond au layer "Model" de MVC.

Elle nedoit pas savoir qu'elle répond indirectement à des requêtes HTTP, elle doit se contenter d'implémenter les règles métier, ainsi que la communication avec la BDD pour la gestion de son modèle.

Ici, TodoModel implémente directement les requêtes SQL afin de réxupérer des données qu'elles renvoie par des fonctions qu'elle expose.

Ces fonctions représentent un `CRUD`: Create, Read, Update, Delete.

## Amélioration possible

### Les exceptions

Elles sont nommées `HTTPException` mais se retrouvent quand même dans le modèle.

C'est mieux que si on renvoyait la réponse HTTP depuis le modèle, mais ça fait apparaitre la notion de HTTP dans le modèle, ce qui doit être évité.
Pour éviter cela, on pourrait faire des `DatabaseExceptions`, par exemple, et réserver les `HTTPExceptions` aux controlleurs.

De plus peu de cas sont réellement gérés et les messages d'erreurs ne sont pas précis. Notre API devrait refléter un peu mieux ces [contraintes](https://www.rfc-editor.org/rfc/rfc7807)

Dans le même élan, un pourrait implémenter une meilleur validation.

### Séparations

Plusieur éléments méritent d'être séparés dans ce projet :

On pourrait réunir la logique par entité, avoir un todo-model.php, et un todo-controller.php.
On aurait alors simplement le routeur dans le index.php.

Les controlleurs pourraient être gérés par des classes pour mieux gérer l'instantiation des TodoModels.

Les TodoModels gèrent à la fois la logique métier, la transformation des données, et la communication avec la base de donnée.
Pour rendre un code plus maintenable, il serait interessant de séparer ça :

Un TodoRepository s'occuperait de la communication avec la base de données, et un TodoService de la communication avec le controlleur. De ce fait, si on change de moteur de BDD, on n'aura qu'a réimplémenter un Repository.

Actuellement, on n'utilise pas de classe pour transporter les instances de Todo, mais cela sera necessaire pour abstraire la couche base de donnée, et avoir un type indépendant de la base de données qui arriven en retour pour le controlleur.

Lors du prochain cours, nous parlerons de tous ces concepts, et nous verrons une meilleure façon de coder ce projet.

# 2023_Api_Project : Syllabus
Vous devez développer un API web pour la solution logicielle d'une entreprise.<br>
<br>L'entreprise en question gère des locations d'appartement à court terme, généralement quelque jours.<br>
Plusieurs clients terminaux se connecteront à votre API : 
- Un backoffice pour les administrateurs,
- une application mobile pour les propriétaires,
- un site web publique pour les clients.<br><br>

Cette application possèdera un backoffice accessible par des administrateurs permettant d'ajouter des
appartements. <br> Pour chaque appartement, on sauvegarde
- sa superficie,
- le nombre de personnes qu'il peut loger,
- une addresse,
- une disponibilité, 
- et un prix à la nuit.<br><br>

On aura aussi :
- un enregistrement des réservations,
- avec une date de début,
- une date de fin,
- un client associé,
- un prix.
- Un utilisateur aura un rôle qui décide de ses droits.<br><br>

Un client peut faire
- une reservation, et
- consulter les apaprtements.<br><br>

Un interne pourra
- changer le prix ou les informations d'un appartement ,
- le rendre disponible ou indisponible,
- voire ajouter un nouvel appartement.<br><br>

Il y aura aussi un compte "propriétaire" qui 
- peut rendre l'appartement indisponible à la réservation pour le moment s'il lui appartient.<br><br>

Vous devez implémenter un **système d'authentification** de votre choix.<br>
Votre projet devra suivre la séparation de logique vue en cours: Vous devrez **abstraire la partie HTTP** et
la partie **base de donnée**.<br>
Vous utiliserez la technologie de votre choix mais vous devrez justifier que le code est bien de vous.
Vous fournirez avec votre projet une extraction **Insomnia** ou Postman contenant les requêtes que propose
votre API.<br>
Vous devrez faire une présentation qui justifie de vos choix de design et d'implémentation dans le cadre
du cours.

# Ressources
- https://www.openapis.org/
- Les RFCs
  
# Ressources du Cours
- Web API
https://gist.github.com/Vagahbond/6a7f8dca60322e244bf72963f6f161fc

- API avec une BDD
https://gist.github.com/Vagahbond/be32cd79d31559ef0da36fec8d6cc492

- Amélioration de votre API en projet MVC
https://gist.github.com/Vagahbond/9a4f62b83c560273c924af513aef1a31

- REST API
https://gist.github.com/Vagahbond/8142af91fa86626eddaddb44cc4dd1e3

```bash
cryin rn
```

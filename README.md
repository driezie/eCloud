# eCloud (Eerste 3 weken)

In project ga je in drie weken een web-interface voor een cloud storage systeem maken. Je kunt je laten inspireren door bestaande systemen als Dropbox, Google Drive en OneDrive, maar je mag ook zelf het wiel opnieuw uitvinden. Dit project voor je individueel uit.

## In Development voor ItsLearning

Projectbeschrijving

Deze opdracht gaat over het gebruikersdeel. In het volgende project maken we het beheerdersdeel.

Je hebt vast zelf wel eens wat bestanden online opgeslagen via één van de bovengenoemde providers. Wat komt daar allemaal bij kijken? Denk goed na over hoe je het programma zo gebruikersvriendelijk mogelijk kunt maken. Maak voor jezelf een plan van aanpak, functioneel en technisch ontwerp. Vervolgens ga je aan de slag met het realiseren van de applicatie en database.

Als gebruiker moet je de mogelijkheid hebben om je te registreren. Na inloggen kun je een overzicht van je bestanden bekijken, bestanden toevoegen (uploaden) of verwijderen,  een bestand downloaden en bestanden delen met anderen op basis van een e-mailadres. Ook is er een overzicht van de bestanden die met jou zijn gedeeld. Deze kun je zien en downloaden en bij verwijderen wordt de koppeling van het delen verwijderd, maar het bestand blijft wel bij de eigenaar staan.
Denk goed na over de manier van opslaan van bestanden.

Verdeel de user story's over meerdere sprints.

 

Het is belangrijk dat de applicatie online staat! 

 

Doelen

Je kunt een Cloud storage website ontwikkelen.
Je hebt aandacht voor security aspecten in een webapplicatie en kunt deze toepassen.
Je kunt je keuze voor de manier van opslaan van bestanden onderbouwen.
Je maakt een omgeving voor users en voor beheerders.
Je kunt een de volledige documentatie schrijven en opleveren.

Beoordeling

Bij de beoordeling zal worden gelet op wat we in de lessen behandeld hebben.

Gebruik een veilige manier om wachtwoorden op te slaan
Werk volgens het DRY principe: gebruik functies, includes, classes
Benader je database via PDO in een functie of een singleton klasse.
Gebruik een database user met beperkte rechten voor de verbinding.
Zorg voor een afgewerkt product met een goede user interface.
​

Producten

Projectplan
Doelstelling, activiteiten, organisatie, planning
Functioneel ontwerp
Product backlog, structuur van je applicatie, functionaliteit per pagina. Maak gebruik van wireframes / mockups en user stories om de functionaliteit te beschrijven.
Technisch ontwerp
Gebruikte libraries / frameworks, codeafspraken, ER-diagram, class diagram, activitiy diagram
Applicatie
Website met PHP, HTML, CSS, etc
Database
Structuur en data
Testplan en testresultaten
Programma van eisen

Functionele Eisen 

Op de voorpagina moet een ongeregistreerde gebruiker snel weten waarvoor de website dient en waar hij kan registreren. 
Vanaf de voorpagina moet een geregistreerde gebruiker snel kunnen navigeren naar de inlogpagina. 
Bovenaan de website staat een navigatiebalk waarmee de niet ingelogde gebruiker kan navigeren naar de registratiepagina en de inlogpagina en een wél ingelogde gebruiker kan via daar naar zijn eigen bestanden navigeren en naar de gedeelde bestanden. 
Gebruikers moeten zich kunnen registreren met naam en e-mailadres bij de clouddienst om een account aan te maken. 
Gebruikers moeten kunnen inloggen om hun bestanden te kunnen zien en te beheren. Hierbij komt vanzelfsprekend bij dat er uitgelogd kan worden. 
Als de gebruiker inlogt, moet die worden doorgestuurd naar een overzicht van zijn persoonlijke bestanden. 
De gebruiker heeft naast het overzicht met eigen bestanden een overzicht van bestanden die door andere gebruikers gedeeld zijn met hem. De gebruiker kan enkel deze bestanden downloaden of verwijderen uit hun gedeelde bestanden, waarna slechts de deling wordt opgeheven en géén bestanden verwijderd worden. 
Gebruikers moeten via een knop en een interface bestanden kunnen uploaden naar hun cloud, welke vervolgens in hun persoonlijke overzicht komen te staan. 
In het overzicht met persoonlijke bestanden moeten gebruikers op een knop kunnen klikken om een bestand te downloaden, verwijderen of te delen met anderen. 
De gebruiker moet eigen bestanden kunnen verwijderen uit de cloud. Let op: wanneer het bestand gedeeld is, moet het bestand ook bij andere gebruikers uit het overzicht verdwijnen. 
De gebruiker moet eigen bestanden en bestanden die gedeeld zijn met hem kunnen downloaden naar zijn computer en telefoon. 
Bij het delen van een bestand moet de gebruiker een e-mailadres in kunnen vullen van een gebruiker waarmee hij het bestand wilt delen. Bij het bevestigen hiervan is in het vervolg het desbetreffende bestand te vinden in het gedeelde overzicht van de gebruiker met het ingevulde e-mailadres. Als notificatie wordt er een e-mail verstuurd.
Bij het onderdeel 'delen'  moet een lijst staan met de gebruikers waarmee het geselecteerde bestand gedeeld is. Hier moet gebruiker 1 een deling met een persoon op kunnen heffen, waarna het bestand niet meer terug te vinden is in het gedeelde overzicht van gebruiker 2. 
Technische Eisen 

De cloud service moet geprogrammeerd worden in PHP. 
Voor de layout mag een CSS framework gebruikt worden, zoals Bootstrap. 
Het mag op geen enkele manier mogelijk zijn om naar pagina’s te navigeren waar de gebruiker geen rechten voor heeft. 
Er mag geen enkele vorm van SQL injection mogelijk zijn. 
Er wordt in de code gebruik gemaakt van duidelijke comments, waarmee duidelijk wordt wat het codeblok doet.  
Voor het communiceren met de database moet een PDO verbinding gebruikt worden, in een functie of klasse. 
Randvoorwaarden 

De webapplicatie moet online staan bij de oplevering. 
De opdrachtgever is tijdens het proces beschikbaar voor feedback en bijsturing van het project. 
De website ondergaat een uitgebreide acceptatietest gebaseerd op de opgestelde eisen. 

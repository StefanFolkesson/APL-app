# APL-app
A 10 weeks assignment to create a APL -app

## A react front end to this Service is being developed here:
https://github.com/StefanFolkesson/react-apl-app

Grundern är klar: 
Jag har en 
create/edit/delete för alla tabeller och i olika nivåer. 
jag har även read för det mesta. 
Det som eventuellt behvös är specifika reads som jag ser när jag ser vad jag vill ha i frontend. 

Det jag har undvikit (medvetet ) är undantaget av spcifika data (där i även skapandet och redigerandet av data i detta)

# indata
jag använder request så konsumenten av tjänsten kan skicka med post eller get
TODO: Dokumentation

# utdata
Skall sätta en standard för utmatning. Jag kommer använda JSON.

strukturen kommer bli 

number representeras av 0 - ok 1-fel 2 - utloggad

{
    "version":1,
    "status":number,
    "data:{
        row:{
            ....
        }
        ...
    }
}

exempel:
{"version":"1","status":"0","data":{"0":{"pnr":"121212-1212","enamn":"kalle"},"1":{"pnr":"232323-2323","enamn":"yalle"}}}

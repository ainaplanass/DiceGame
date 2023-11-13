![dce](https://github.com/ainaplanass/DiceGame/assets/82839054/fd5e795c-8085-40d0-934f-0948d9a02274| width=50)

# Dice Game Api

In this game the player rolls two dice, if they sum 7 he wins, otherwise loses.

## Routes

- POST /players : create a player.
- PUT /players/{id} : Modifies the player's name.
- POST /players/{id}/games/ : a specific player rolls the dice.
- DELETE /players/{id}/games: removes player runs.
- GET /players: returns the list of all players in the system with their average success rate
- GET /players/{id}/games: returns the list of plays by a player.
- GET /players/ranking: returns the average ranking of all players in the system. That is, the average success rate.
- GET /players/ranking/loser: returns the player with the worst success rate.
- GET /players/ranking/winner: returns the player with the best success rate.
- 
## Technology 

- **PHP**: This 4project is developed using PHP.
- **Laravel**: This project is developed using Laravel framework.
- **Passport**: Access to APIs is protected by token-based credentials.
- **Spatie**: User permissions and roles (admin/player)
- 

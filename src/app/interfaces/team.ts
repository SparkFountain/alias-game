import { Player } from './player';

export interface Team {
  name: string;
  color: string;
  players: Player[];
}

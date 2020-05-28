import { Player } from './player';

export interface Team {
  name: string;
  active: boolean;
  color: string;
  players: Player[];
  remainingCards: number;
}

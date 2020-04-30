import { Player } from './player';
import { Team } from './team';

export interface ActiveSession {
  name: string;
  creator: string;
  horizontal: number;
  vertical: number;
  theme: string;
  teams: Team[];
}

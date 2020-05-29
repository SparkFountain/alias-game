import { Team } from './team';
import { Card } from './card';

export interface ActiveSession {
  name: string;
  creator: string;
  horizontal: number;
  vertical: number;
  theme: string;
  teams: Team[];
  cards: Card[];
  started: boolean;
  description: {
    term: string;
    amount: number;
    accepted: boolean;
    denied: boolean;
  };
}

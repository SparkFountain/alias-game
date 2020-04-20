import { Team } from './team';

export interface JoinSession {
  name: string;
  creator: string;
  teams: Team[];
}

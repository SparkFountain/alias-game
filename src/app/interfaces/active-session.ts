export interface ActiveSession {
  name: string;
  creator: string;
  horizontal: number;
  vertical: number;
  theme: string;
  seed: number;
  teams: Array<{
    name: string;
    color: string;
    remainingCards: number;
    players: string[];
  }>;
}

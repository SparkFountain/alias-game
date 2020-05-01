export interface HistoryEvent {
  term: string;
  teamColor: string;
  hits: {
    teamA: number;
    teamB: number;
    neutral: number;
    black: number;
  };
}

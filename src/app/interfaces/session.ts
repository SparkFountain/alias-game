export interface Session {
  creator: string;
  activeUser: boolean;
  name: string;
  horizontal: number;
  vertical: number;
  theme: string;
  teamOneName: string;
  teamOneColor: string;
  teamTwoName: string;
  teamTwoColor: string;
}

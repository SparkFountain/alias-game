export interface Player {
  name: string;
  active: boolean;
  selected: {
    x: number;
    y: number;
  };
}

/*
*  Copyright (C) 1998-2019 by Northwoods Software Corporation. All Rights Reserved.
*/

import * as go from '../release/go';
import { ParallelRouteLink } from './ParallelRouteLink';

export function init() {
  if ((window as any).goSamples) (window as any).goSamples();  // init for these samples -- you don't need to call this

  const $ = go.GraphObject.make;

  const myDiagram =
    $(go.Diagram, 'myDiagramDiv',
      {
        'undoManager.isEnabled': true
      });

  myDiagram.nodeTemplate =
    $(go.Node, 'Auto',
      new go.Binding('location', 'loc', go.Point.parse),
      $(go.Shape,
        {
          portId: '',
          fromLinkable: true, toLinkable: true,
          fromLinkableDuplicates: true, toLinkableDuplicates: true,
          cursor: 'pointer'
        },
        new go.Binding('fill', 'color')),
      $(go.TextBlock,
        { margin: 8 },
        new go.Binding('text'))
    );

  myDiagram.linkTemplate =
    $(ParallelRouteLink,
      {
        relinkableFrom: true, relinkableTo: true,
        reshapable: true // , resegmentable: true
      },
      $(go.Shape, { strokeWidth: 2 },
        new go.Binding('stroke', 'fromNode', (node) => node.port.fill).ofObject()),
      $(go.Shape, { toArrow: 'OpenTriangle', strokeWidth: 1.5 },
        new go.Binding('stroke', 'fromNode', (node) => node.port.fill).ofObject())
    );

  myDiagram.model = new go.GraphLinksModel(
    [
      { key: 1, text: 'Alpha', color: 'lightblue', loc: '0 0' },
      { key: 2, text: 'Beta', color: 'orange', loc: '130 70' },
      { key: 3, text: 'Gamma', color: 'lightgreen', loc: '0 130' }
    ],
    [
      { from: 1, to: 2 },
      { from: 2, to: 1 },
      { from: 1, to: 3 },
      { from: 1, to: 3 },
      { from: 3, to: 1 },
      { from: 1, to: 3 },
      { from: 1, to: 3 }
    ]);
}
